<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingFile\ReadingFileCSV;

use DateTimeImmutable;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Collection;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingFile\DTOAutoPartsFile\AutoPartsFile;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;

class ReadingFileCSV
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

        $data_file = [];
        foreach (file($file) as $key => $value) {

            $data_file[$key] = str_getcsv($value);
        }

        $input_errors->emptyEntity($data_file);

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
                if (strpos($value['4'], ',')) {
                    $price = (float)str_replace(',', '.', $value['4']);
                } else {
                    $price = (float)$value['4'];
                }
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
