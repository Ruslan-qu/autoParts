<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingEmail\DTOAutoPartsEmail;

use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;

abstract class MapAutoPartsEmail
{
    public function __construct(array $data = [])
    {
        $this->load($data);
    }

    private function load(array $data)
    {

        foreach ($data as $key => $value) {

            $input_errors = new InputErrorsAutoPartsWarehouse;
            $input_errors->emptyData($value)->imapOpenErrors()->instanceofEmail($value);

            $this->$key = $value;
        }
    }
}
