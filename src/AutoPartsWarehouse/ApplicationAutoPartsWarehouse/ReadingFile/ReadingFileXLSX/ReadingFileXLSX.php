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
        $data_file_xlsx = [];
        foreach ($xls_values as $key => $value) {

            if (empty($value['A'])) {
                $part_name = null;
            } else {
                $part_name = $value['A'];
            }

            if (empty($value['B'])) {
                $manufacturer = null;
            } else {
                $manufacturer = $value['B'];
            }

            if (empty($value['C'])) {
                $part_number = null;
            } else {
                $part_number = $value['C'];
            }

            if (empty($value['D'])) {
                $quantity = null;
            } else {
                $quantity = $value['D'];
            }

            if (empty($value['E'])) {
                $price = null;
            } else {
                $price = $value['E'];
            }

            if (empty($value['F'])) {
                $counterparty = null;
            } else {
                $counterparty = $value['F'];
            }

            if (empty($value['G']) || strtotime($value['G']) === false) {
                $date_receipt_auto_parts_warehouse = null;
            } else {
                $date_receipt_auto_parts_warehouse = new DateTimeImmutable($value['G']);
            }

            if (empty($value['H'])) {
                $payment_method = null;
            } else {
                $payment_method = $value['H'];
            }

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
