<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingFile\ReadingFileXML;

use DateTimeImmutable;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Collection;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingFile\DTOAutoPartsFile\AutoPartsFile;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;

class ReadingFileXML
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

        $data_file = simplexml_load_file($file);
        //dd((array)$data_file->table);
        $input_errors->emptyEntity($data_file);

        return $this->mapCSVValues($data_file);
    }

    private function mapCSVValues($data_file): array
    {
        $input_errors = new InputErrorsAutoPartsWarehouse;
        $data_file_xml = [];
        $data_file_xml_key = 0;
        foreach ($data_file as $key => $value) {

            $input_errors->emptyFileCells($value->part_name);
            $part_name = (string)$value->part_name;

            $input_errors->emptyFileCells($value->manufacturer);
            $manufacturer = (string)$value->manufacturer;

            $input_errors->emptyFileCells($value->part_number);
            $part_number = (string)$value->part_number;

            $input_errors->emptyFileCells($value->quantity);
            $quantity = (string)$value->quantity;

            $input_errors->emptyFileCells($value->price);
            if (strpos((string)$value->price, ',')) {
                $price = (float)str_replace(',', '.', (string)$value->price);
            } else {
                $price = (float)$value->price;
            }

            $input_errors->emptyFileCells($value->counterparty);
            $counterparty = (string)$value->counterparty;

            $input_errors->emptyFileCellsDate($value->date_receipt_auto_parts_warehouse);
            $date_receipt_auto_parts_warehouse = new DateTimeImmutable($value->date_receipt_auto_parts_warehouse);

            $input_errors->emptyFileCells($value->payment_method);
            $payment_method = (string)$value->payment_method;

            $data_file_xml[$data_file_xml_key] =
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
            $data_file_xml_key++;
        }
        return $data_file_xml;
    }
}
