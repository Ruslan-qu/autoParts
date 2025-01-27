<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse;

use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use IMAP\Connection;

class InputErrorsAutoPartsWarehouse
{
    public function errorDuplicate(int $count_duplicate): static
    {
        if ($count_duplicate != 0) {

            $arr_data_errors = ['Error' => 'Номер детали уже существует 
            или Изменить на существующий номер детали нельзя'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new ConflictHttpException($json_arr_data_errors);
        }

        return $this;
    }

    public function errorValidate(ConstraintViolationList $errors_validate): static
    {
        if ($errors_validate->count()) {
            $validator_errors = [];
            foreach ($errors_validate as $key => $value_error) {

                $validator_errors[$key] = [
                    $value_error->getPropertyPath() => $value_error->getMessage()
                ];
            }
            $json_data_errors = json_encode($validator_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_data_errors);
        }

        return $this;
    }

    public function emptyData($data): static
    {
        if (empty($data)) {

            $arr_data_errors = ['Error' => 'Пустые данные'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $this;
    }

    public function notEmptyData($data): static
    {
        if (!empty($data)) {

            $arr_data_errors = ['Error' => 'Данные не найдены'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $this;
    }

    public function emptyDataTwo(string $where_parameter, array $arr_parameters): static
    {
        if (empty($where_parameter) || empty($arr_parameters)) {

            $arr_data_errors = ['Error' => 'Пустые данные'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $this;
    }

    public function emptyEntity($еntity): static
    {
        if (empty($еntity)) {

            $arr_data_errors = ['Error' => 'Данные не найдены'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $this;
    }

    public function propertyExistsEntity($еntity, $key, $data): static
    {
        if (!property_exists($еntity, $key)) {

            $arr_data_errors = ['Error' => 'Свойство ' . $key .
                '  не существует в' . $data . 'объекте.'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $this;
    }

    public function comparingClassNames($className, $value, $key): static
    {

        if (stripos(get_class($value), $className) === false) {

            $arr_data_errors = ['Error' => 'Значение ' . $key .
                ' должно быть объектом класса ' . $className . '.'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $this;
    }

    public function checkingDataIsTable($data_delete): static
    {
        if (!empty($data_delete)) {

            $arr_data_errors = ['Error' => 'Удаление или Изменение запрещено, используется в других таблицах'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $this;
    }

    public function determiningFileType($file): static
    {
        if (filetype($file) != 'file') {

            $arr_data_errors = ['Error' => 'Неверный тип, файл должен быть тип File'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $this;
    }

    public function determiningFileExtension(): static
    {
        $arr_data_errors = ['Error' => 'Неверное расширение файла, должно быть xlsx, xml, csv, ods'];
        $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
        throw new UnprocessableEntityHttpException($json_arr_data_errors);

        return $this;
    }

    public function fileOpenErrors($file_open): static
    {
        if ($file_open != true) {

            $arr_data_errors = ['Error' => 'Ошибка: ' . $file_open . ', файл не открывается'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $this;
    }

    public function fileStreamErrors($file_stream): static
    {
        if ($file_stream == false) {

            $arr_data_errors = ['Error' => 'Ошибка чтения файла'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $this;
    }

    public function emptyAndNotCount($data): static
    {
        if (empty($data) && !count($data)) {

            $arr_data_errors = ['Error' => 'Данные не найдены'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $this;
    }

    public function countArr($entityData, $count_key): static
    {
        if (count($entityData) != $count_key) {

            $arr_data_errors = ['Error' => 'Данные в базе данных не сохранены'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $this;
    }

    public function emptyFileCells(string $value): static
    {
        if (empty($value)) {

            $arr_data_errors = ['Error' => 'Фаил содержит пустые ячейки'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $this;
    }

    public function emptyFileCellsDate(string $value): static
    {
        if (empty($value) || strtotime($value) === false) {

            $arr_data_errors = ['Error' => 'Фаил содержит пустую ячейку дата'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $this;
    }

    public function emptyFileCellsKey(array $value, string $key): static
    {
        if (empty($value[$key])) {

            $arr_data_errors = ['Error' => 'Фаил содержит пустые ячейки'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $this;
    }

    public function emptyFileCellsDateKey(array $value, string $key): static
    {
        if (empty($value[$key]) || strtotime($value[$key]) === false) {

            $arr_data_errors = ['Error' => 'Фаил содержит пустую ячейку дата'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $this;
    }

    public function instanceofEmail($imap): static
    {
        if (!($imap instanceof Connection)) {

            $arr_data_errors = ['Error' => 'Неверный тип, файл должен быть тип Connection'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $this;
    }

    public function imapOpenErrors(): static
    {
        if (imap_errors() != false) {

            $arr_data_errors = ['Error' => imap_errors()];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $this;
    }

    public function emptyHeadersFrom($headers, int $key): static
    {

        if (empty($headers->from[$key]->mailbox)) {

            $arr_data_errors = ['Error' => 'Данные headers->from пустые'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $this;
    }

    public function emptyDataEmail(array $value, int $key): static
    {
        if (empty($value[$key])) {

            $arr_data_errors = ['Error' => 'Емаил содержит пустые данные'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $this;
    }

    public function isMessageMarkedDeletion($imap_delete): static
    {
        if ($imap_delete != true) {

            $arr_data_errors = ['Error' => 'Пометить сообщение для удаления не удалось'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $this;
    }

    public function isIMAPStreamClosed($imap_delete): static
    {
        if ($imap_delete != true) {

            $arr_data_errors = ['Error' => 'Закрыть поток IMAP не удалось'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $this;
    }
}
