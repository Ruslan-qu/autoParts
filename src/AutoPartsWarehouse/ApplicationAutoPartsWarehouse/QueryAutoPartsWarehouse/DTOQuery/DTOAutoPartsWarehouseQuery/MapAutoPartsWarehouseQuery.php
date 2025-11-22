<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOAutoPartsWarehouseQuery;

use ReflectionProperty;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartNumbersFromManufacturers;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse;

abstract class MapAutoPartsWarehouseQuery
{

    public function __construct(array $data = [])
    {
        $this->load($data);
    }

    private function load(array $data)
    {
        $input_errors = new InputErrorsAutoPartsWarehouse;
        foreach ($data as $key => $value) {

            if (!empty($value)) {

                if ($key === 'is_customer_order') {
                    $input_errors->propertyExistsEntity(AutoPartsWarehouse::class, $key, 'AutoPartsWarehouse');

                    $refl = new ReflectionProperty(AutoPartsWarehouse::class, $key);
                } else {
                    $input_errors->propertyExistsEntity(PartNumbersFromManufacturers::class, $key, 'PartNumbersFromManufacturers');

                    $refl = new ReflectionProperty(PartNumbersFromManufacturers::class, $key);
                }

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
