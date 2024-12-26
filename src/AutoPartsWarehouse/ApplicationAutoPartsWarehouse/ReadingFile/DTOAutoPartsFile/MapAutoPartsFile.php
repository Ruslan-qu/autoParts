<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingFile\DTOAutoPartsFile;

use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;

abstract class MapAutoPartsFile
{
    public function __construct(array $data = [])
    {
        $this->load($data);
    }

    private function load(array $data)
    {

        foreach ($data as $key => $value) {

            if (!empty($value)) {

                $input_errors = new InputErrorsAutoPartsWarehouse;
                $input_errors->determiningFileType($value);

                $this->$key = $value;
            }
        }
    }
}
