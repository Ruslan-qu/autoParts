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

        $a = str_replace("<table:table-row table:style-name=\"[a-zA-Z\d]+\">", "<text:p> <\/text:p>", $data);
        dd($a);
        preg_match_all(
            "/(<text:p>)(.*?)(<\/text:p>)/",
            str_replace("<table:table-row table:style-name=\"[a-zA-Z\d]+\">", "<text:p> <\/text:p>", $data),
            $matches,
            PREG_SET_ORDER
        );
        dd($matches);
        /* preg_match_all(
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
            $arr_explode[$key] = explode("<t", $value);
        }*/
        dd($arr_explode);


        return $this->mapCSVValues($data_file);
    }

    private function mapCSVValues($data_file): array
    {
        $data_file_csv = [];
        foreach ($data_file as $key => $value) {

            if (empty($value['0'])) {
                $part_name = null;
            } else {
                $part_name = $value['0'];
            }

            if (empty($value['1'])) {
                $manufacturer = null;
            } else {
                $manufacturer = $value['1'];
            }

            if (empty($value['2'])) {
                $part_number = null;
            } else {
                $part_number = $value['2'];
            }

            if (empty($value['3'])) {
                $quantity = null;
            } else {
                $quantity = $value['3'];
            }

            if (empty($value['4'])) {
                $price = null;
            } else {
                $price = $value['4'];
            }

            if (empty($value['5'])) {
                $counterparty = null;
            } else {
                $counterparty = $value['5'];
            }

            if (empty($value['6']) || strtotime($value['6']) === false) {
                $date_receipt_auto_parts_warehouse = null;
            } else {
                $date_receipt_auto_parts_warehouse = new DateTimeImmutable($value['6']);
            }

            if (empty($value['7'])) {
                $payment_method = null;
            } else {
                $payment_method = $value['7'];
            }

            $data_file_csv[$key] =
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
        return $data_file_csv;
    }
}
