<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingFile\ReadingFileXLSX;

use DateTimeImmutable;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Collection;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingFile\DTOAutoPartsFile\AutoPartsFile;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;

class ReadingFileXLSX
{
    public function reading(AutoPartsFile $autoPartsFile)
    {
        $file = $autoPartsFile->getFileSave();

        /* Подключаем валидацию и прописываем условия валидации */
        $validator = Validation::createValidator();
        $input = [
            'file_error' => $file
        ];

        $constraint = new Collection([
            'file_error' => new File(
                maxSize: '64M',
                maxSizeMessage: 'Максимальный размер файла не должен превышать 64м',
            )
        ]);

        $errors_validate = $validator->validate($input, $constraint);
        $input_errors = new InputErrorsAutoPartsWarehouse;
        $input_errors->errorValidate($errors_validate);

        $zip = new \ZipArchive();

        $input_errors->fileOpenErrors($zip->open($file));

        /*Прочитать строковые значения*/
        $str_values = [];
        $fp = $zip->getStream('xl/sharedStrings.xml');
        //dd($fp);
        /*$xml = simplexml_load_string($fp);
        $sheetData = (array)($xml->sheetData);
        $a = (array)$sheetData['row']['0'];
        $b = (array)$a['c']['0'];
        dd(!isset($b['v']));*/
        $input_errors->fileStreamErrors($fp);

        $data = '';
        while (!feof($fp)) {
            $data .= fread($fp, filesize($file));
        }
        fclose($fp);
        $xml = simplexml_load_string($data);

        $input_errors->emptyAndNotCount($xml->si);

        foreach ($xml->si as $data) {
            $data = (array)$data;
            $str_values[] = $data['t'];
        }

        /*Прочитать значения из первой страницы документа*/
        $xls_values = [];
        $fp = $zip->getStream('xl/worksheets/sheet1.xml');

        $input_errors->fileStreamErrors($fp);

        $data = '';
        while (!feof($fp)) {
            $data .= fread($fp, filesize($file));
        }
        fclose($fp);
        $xml = simplexml_load_string($data);

        $input_errors->emptyEntity($xml->sheetData);

        $sheetData = (array)($xml->sheetData);

        $input_errors->emptyAndNotCount($sheetData['row']);
        /* $a = (array)$sheetData['row']['0'];
        dd($a);*/
        foreach ($sheetData['row'] as $row) {
            $row = (array)$row;

            /*Особый случай для одноколоночной страницы*/
            if (!is_array($row['c'])) {
                $row['c'] = array($row['c']);
            }

            foreach ($row['c'] as $col) {
                $col = (array)$col;
                /*Столбец и колонка*/
                preg_match('/([A-Z]+)(\d+)/', $col['@attributes']['r'], $matches);
                //dd($matches);
                /*Строка из списка*/
                if (
                    isset($col['@attributes']['t'])
                    && $col['@attributes']['t'] == 's'
                    && isset($str_values[$col['v']])
                ) {
                    $xls_values[$matches[2]][$matches[1]] = $str_values[$col['v']];
                } elseif (isset($col['v'])) {
                    /*Непосредственное значение*/
                    $xls_values[$matches[2]][$matches[1]] = $col['v'];
                }
            }
        }

        $zip->close();

        $input_errors->emptyEntity($xls_values);

        return $this->mapXlsValues($xls_values);
    }

    private function mapXlsValues($xls_values): array
    {
        $input_errors = new InputErrorsAutoPartsWarehouse;
        $data_file_xlsx = [];
        foreach ($xls_values as $key => $value) {

            $input_errors->emptyFileCellsKey($value, 'A');
            $part_name = $value['A'];


            $input_errors->emptyFileCellsKey($value, 'B');
            $manufacturer = $value['B'];


            $input_errors->emptyFileCellsKey($value, 'C');
            $part_number = $value['C'];


            $input_errors->emptyFileCellsKey($value, 'D');
            $quantity = $value['D'];


            $input_errors->emptyFileCellsKey($value, 'E');
            $price = (float)$value['E'];

            $input_errors->emptyFileCellsKey($value, 'F');
            $counterparty = $value['F'];


            $input_errors->emptyFileCellsDateKey($value, 'G');
            $date_receipt_auto_parts_warehouse = new DateTimeImmutable($value['G']);

            $input_errors->emptyFileCellsKey($value, 'H');
            $payment_method = $value['H'];


            $data_file_xlsx[$key] =
                [
                    'part_name' => $part_name,
                    'manufacturer' => $manufacturer,
                    'part_number' => $part_number,
                    'quantity' => $quantity,
                    'price' => $price,
                    'counterparty' => $counterparty,
                    'date_receipt_auto_parts_warehouse' => $date_receipt_auto_parts_warehouse,
                    'payment_method' => $payment_method
                ];
        }
        return $data_file_xlsx;
    }
}
