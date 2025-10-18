<?php

namespace App\Sales\ApplicationSales\QuerySales\DTOSales\DTOSalesQuery;

use ReflectionProperty;
use App\Sales\DomainSales\DomainModelSales\AutoPartsSold;
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

        foreach ($data as $key => $value) {

            if (!empty($value)) {

                if ($key == 'from_date_sold' || $key == 'to_date_sold') {

                    $refl = new ReflectionProperty(AutoPartsSold::class, $key);
                    $type = $refl->getType()->getName();

                    settype($value, $type);
                    $this->$key = $value;
                }

                if (property_exists(AutoPartsWarehouse::class, $key)) {

                    $refl = new ReflectionProperty(AutoPartsWarehouse::class, $key);
                    $type = $refl->getType()->getName();

                    settype($value, $type);
                    $this->$key = $value;
                }

                if (property_exists(PartNumbersFromManufacturers::class, $key)) {

                    $refl = new ReflectionProperty(PartNumbersFromManufacturers::class, $key);
                    $type = $refl->getType()->getName();

                    settype($value, $type);
                    $this->$key = $value;
                }

                if (property_exists(OriginalRooms::class, $key)) {

                    $refl = new ReflectionProperty(OriginalRooms::class, $key);
                    $type = $refl->getType()->getName();

                    settype($value, $type);
                    $this->$key = $value;
                }
            }
        }
    }
}
