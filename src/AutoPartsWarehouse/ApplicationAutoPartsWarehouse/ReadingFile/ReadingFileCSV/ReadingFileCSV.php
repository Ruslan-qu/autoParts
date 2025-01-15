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
        $input_errors = new InputErrorsAutoPartsWarehouse;
        $data_file_csv = [];
        foreach ($data_file as $key => $value) {

            $input_errors->emptyFileCells($value['0']);
            $part_name = $value['0'];

            $input_errors->emptyFileCells($value['1']);
            $manufacturer = $value['1'];

            $input_errors->emptyFileCells($value['2']);
            $part_number = $value['2'];

            $input_errors->emptyFileCells($value['3']);
            $quantity = $value['3'];

            $input_errors->emptyFileCells($value['4']);
            if (strpos($value['4'], ',')) {
                $price = (float)str_replace(',', '.', $value['4']);
            } else {
                $price = (float)$value['4'];
            }

            $input_errors->emptyFileCells($value['5']);
            $counterparty = $value['5'];

            $input_errors->emptyFileCellsDate($value['6']);
            $date_receipt_auto_parts_warehouse = new DateTimeImmutable($value['6']);

            $input_errors->emptyFileCells($value['7']);
            $payment_method = $value['7'];


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
