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
        $data_file_xml = [];
        $data_file_xml_key = 0;
        dd((array)$data_file->table);
        foreach ($data_file->table as $key => $value) {

            if (empty($value->part_name)) {
                $part_name = null;
            } else {
                $part_name = $value->part_name;
            }

            if (empty($value->manufacturer)) {
                $manufacturer = null;
            } else {
                $manufacturer = $value->manufacturer;
            }

            if (empty($value->part_number)) {
                $part_number = null;
            } else {
                $part_number = $value->part_number;
            }

            if (empty($value->quantity)) {
                $quantity = null;
            } else {
                $quantity = $value->quantity;
            }

            if (empty($value->price)) {
                $price = null;
            } else {
                $price = $value->price;
            }

            if (empty($value->counterparty)) {
                $counterparty = null;
            } else {
                $counterparty = $value->counterparty;
            }

            if (
                empty($value->date_receipt_auto_parts_warehouse)
                || strtotime($value->date_receipt_auto_parts_warehouse) === false
            ) {
                $date_receipt_auto_parts_warehouse = null;
            } else {
                $date_receipt_auto_parts_warehouse = new DateTimeImmutable($value->date_receipt_auto_parts_warehouse);
            }

            if (empty($value->payment_method)) {
                $payment_method = null;
            } else {
                $payment_method = $value->payment_method;
            }

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
