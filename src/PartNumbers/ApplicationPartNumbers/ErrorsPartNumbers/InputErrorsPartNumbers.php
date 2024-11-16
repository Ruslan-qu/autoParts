<?php

namespace App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers;

use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class InputErrorsPartNumbers
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
                '  не существует в ' . $data . ' объекте.'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $this;
    }

    public function comparingClassNames($className, $value, $key): static
    {
        if ($className !== get_class($value)) {

            $arr_data_errors = ['Error' => 'Значение ' . $key .
                ' должно быть объектом класса ' . $className . '.'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $this;
    }
}
