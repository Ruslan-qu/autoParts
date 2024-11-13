<?php

namespace App\Counterparty\ApplicationCounterparty\Errors;

use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class InputErrors
{
    public function errorDuplicate(int $count_duplicate): static
    {
        if ($count_duplicate != 0) {

            $arr_data_errors = ['Error' => 'Поставщик уже существует'];
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

    public function emptyData(int|string $data): static
    {
        if (empty($data)) {

            $arr_data_errors = ['Error' => 'Пустые данные'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $this;
    }

    public function emptyEntity($еntity): static
    {
        if (empty($еntity)) {

            $arr_data_errors = ['Error' => 'Сущность не существует'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $this;
    }

    public function emptyArrEntity(array $arr_еntity): static
    {
        if (empty($arr_еntity)) {

            $arr_data_errors = ['Error' => 'Сущность не существует'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $this;
    }
}