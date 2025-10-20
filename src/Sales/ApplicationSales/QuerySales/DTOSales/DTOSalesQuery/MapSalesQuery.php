<?php

namespace App\Sales\ApplicationSales\QuerySales\DTOSales\DTOSalesQuery;

use ReflectionProperty;
use App\Sales\DomainSales\DomainModelSales\AutoPartsSold;
use App\Sales\ApplicationSales\ErrorsSales\InputErrorsSales;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\OriginalRooms;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartNumbersFromManufacturers;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse;

abstract class MapSalesQuery
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

                if ($key == 'from_date_sold' || $key == 'to_date_sold') {

                    $refl = new ReflectionProperty(AutoPartsSold::class, $key);
                    $type = $refl->getType()->getName();
                }

                if (property_exists(AutoPartsWarehouse::class, $key)) {

                    $refl = new ReflectionProperty(AutoPartsWarehouse::class, $key);
                    $type = $refl->getType()->getName();


                    if (is_object($value)) {

                        $input_errors->comparingClassNames($type, $value, $key);
                        $type = 'object';
                    }
                }

                if (property_exists(PartNumbersFromManufacturers::class, $key)) {

                    $refl = new ReflectionProperty(PartNumbersFromManufacturers::class, $key);
                    $type = $refl->getType()->getName();


                    if (is_object($value)) {

                        $input_errors->comparingClassNames($type, $value, $key);
                        $type = 'object';
                    }
                }

                if (property_exists(OriginalRooms::class, $key)) {

                    $refl = new ReflectionProperty(OriginalRooms::class, $key);
                    $type = $refl->getType()->getName();

                    if (is_object($value)) {

                        $input_errors->comparingClassNames($type, $value, $key);
                        $type = 'object';
                    }
                }
                settype($value, $type);
                $this->$key = $value;
            }
        }
    }
}
