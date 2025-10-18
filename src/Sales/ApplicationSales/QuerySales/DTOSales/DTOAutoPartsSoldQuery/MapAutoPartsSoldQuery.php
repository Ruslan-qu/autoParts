<?php

namespace App\Sales\ApplicationSales\QuerySales\DTOSales\DTOAutoPartsSoldQuery;

use ReflectionProperty;
use App\Sales\DomainSales\DomainModelSales\AutoPartsSold;
use App\Sales\ApplicationSales\ErrorsSales\InputErrorsSales;

abstract class MapAutoPartsSoldQuery
{

    public function __construct(array $data = [])
    {
        $this->load($data);
    }

    private function load(array $data)
    {
        $input_errors = new InputErrorsSales;

        foreach ($data as $key => $value) {

            if (!empty($value)) {

                $input_errors->propertyExistsEntity(AutoPartsSold::class, $key, 'AutoPartsSold');

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
