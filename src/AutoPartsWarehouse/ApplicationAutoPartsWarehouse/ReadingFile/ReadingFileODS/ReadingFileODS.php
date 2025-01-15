<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingFile\ReadingFileODS;

use DateTimeImmutable;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Collection;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingFile\DTOAutoPartsFile\AutoPartsFile;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;

class ReadingFileODS
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

        $data = $zip->getFromName('content.xml');

        $input_errors->fileStreamErrors($data);

        preg_match_all(
            "/(<table:table-row table:style-name=\"[a-zA-Z\d]+\">)(.*?)(<\/table:table-row>)/",
            $data,
            $matches,
            PREG_SET_ORDER
        );
        $input_errors->emptyEntity($matches);

        $arr_string_matches = [];
        foreach ($matches as $key => $value) {
            if (str_contains($value['2'], '<text:p>') === true) {
                $arr_string_matches[$key] = $value['2'];
            }
        }
        $input_errors->emptyEntity($arr_string_matches);

        foreach ($arr_string_matches as $key => $value) {

            preg_match_all(
                "/(<text:p>)(.*?)(<\/text:p>)/",
                preg_replace("<table:table-cell table:style-name=\"[a-zA-Z\d]*\"/>", "<text:p></text:p>", $value),
                $matches,
                PREG_SET_ORDER
            );
            $arr_processed[$key] = $matches;
        }
        $zip->close();

        return $this->mapCSVValues($arr_processed);
    }

    private function mapCSVValues($data_file): array
    {
        $input_errors = new InputErrorsAutoPartsWarehouse;
        $data_file_ods = [];
        foreach ($data_file as $key => $value) {

            $input_errors->emptyFileCells($value['0']['2']);
            $part_name = $value['0']['2'];

            $input_errors->emptyFileCells($value['1']['2']);
            $manufacturer = $value['1']['2'];

            $input_errors->emptyFileCells($value['2']['2']);
            $part_number = $value['2']['2'];

            $input_errors->emptyFileCells($value['3']['2']);
            $quantity = $value['3']['2'];

            $input_errors->emptyFileCells($value['4']['2']);
            if (strpos($value['4']['2'], ',')) {
                $price = (float)str_replace(',', '.', $value['4']['2']);
            } else {
                $price = (float)$value['4']['2'];
            }

            $input_errors->emptyFileCells($value['5']['2']);
            $counterparty = $value['5']['2'];

            $input_errors->emptyFileCellsDate($value['6']['2']);
            $date_receipt_auto_parts_warehouse = new DateTimeImmutable($value['6']['2']);

            $input_errors->emptyFileCells($value['7']['2']);
            $payment_method = $value['7']['2'];

            $data_file_ods[$key] =
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

        return $data_file_ods;
    }
}
