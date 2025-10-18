<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOPartNumbersCommand;

use ReflectionProperty;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartNumbersFromManufacturers;

abstract class MapPartNumbersCommand
{

    public function __construct(array $data = [])
    {
        $this->load($data);
    }

    private function load(array $data)
    {

        $input_errors = new InputErrorsPartNumbers;

        foreach ($data as $key => $value) {

            if (!empty($value)) {


                $input_errors->propertyExistsEntity(PartNumbersFromManufacturers::class, $key, 'PartNumbersFromManufacturers');

                $refl = new ReflectionProperty(PartNumbersFromManufacturers::class, $key);
                $type = $refl->getType()->getName();

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
