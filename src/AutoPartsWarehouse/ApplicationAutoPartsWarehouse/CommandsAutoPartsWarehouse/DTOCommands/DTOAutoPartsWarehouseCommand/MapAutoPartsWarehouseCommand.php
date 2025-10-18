<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DTOCommands\DTOAutoPartsWarehouseCommand;

use ReflectionProperty;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse;

abstract class MapAutoPartsWarehouseCommand
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
                $input_errors->propertyExistsEntity(AutoPartsWarehouse::class, $key, 'AutoPartsWarehouse');

                $refl = new ReflectionProperty(AutoPartsWarehouse::class, $key);
                $type = $refl->getType()->getName();

                if (gettype($value) == 'double' || gettype($value) == 'float') {

                    $value = round($value * 100);
                }

                if (is_object($value)) {

                    $input_errors->comparingClassNames($type, $value, $key);
                    $type = 'object';
                }

                settype($value, $type);
                $this->$key = $value;
            }
        }
    }
}
