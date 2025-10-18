<?php

namespace App\Sales\ApplicationSales\CommandsSales\DTOAutoPartsSoldCommand;

use ReflectionProperty;
use App\Sales\DomainSales\DomainModelSales\AutoPartsSold;
use App\Sales\ApplicationSales\ErrorsSales\InputErrorsSales;

abstract class MapAutoPartsSoldCommand
{
    public function __construct(array $data = [])
    {
        $this->load($data);
    }

    private function load(array $data)
    {
        $input_errors = new InputErrorsSales;

        foreach ($data as $key => $value) {

            $input_errors->propertyExistsEntity(AutoPartsSold::class, $key, 'AutoPartsSold');

            if (!empty($value)) {

                $refl = new ReflectionProperty(AutoPartsSold::class, $key);
                $type = $refl->getType()->getName();

                if (is_object($value)) {

                    $input_errors->comparingClassNames($type, $value, $key);
                    $type = 'object';
                }

                if (gettype($value) == 'double' || gettype($value) == 'float') {

                    $value = round($value * 100);
                }

                settype($value, $type);
                $this->$key = $value;
            }
        }
    }
}
