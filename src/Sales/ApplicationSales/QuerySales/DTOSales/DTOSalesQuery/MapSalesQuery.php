<?php

namespace App\Sales\ApplicationSales\QuerySales\DTOSales\DTOSalesQuery;

use App\Sales\DomainSales\DomainModelSales\AutoPartsSold;
use Symfony\Component\TypeInfo\TypeResolver\TypeResolver;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
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
        $typeResolver = TypeResolver::create();

        foreach ($data as $key => $value) {

            if (!empty($value)) {

                if ($key == 'from_date_sold' || $key == 'to_date_sold') {

                    $type = $typeResolver->resolve(new \ReflectionProperty(AutoPartsSold::class, 'date_sold'))
                        ->getBaseType()
                        ->getTypeIdentifier()
                        ->value;
                    settype($value, $type);

                    $this->$key = $value;
                }

                if (property_exists(AutoPartsWarehouse::class, $key)) {

                    $type = $typeResolver->resolve(new \ReflectionProperty(AutoPartsWarehouse::class, $key))
                        ->getBaseType()
                        ->getTypeIdentifier()
                        ->value;
                    settype($value, $type);

                    $this->$key = $value;
                }

                if (property_exists(PartNumbersFromManufacturers::class, $key)) {

                    $type = $typeResolver->resolve(new \ReflectionProperty(PartNumbersFromManufacturers::class, $key))
                        ->getBaseType()
                        ->getTypeIdentifier()
                        ->value;
                    settype($value, $type);

                    $this->$key = $value;
                }

                if (property_exists(OriginalRooms::class, $key)) {

                    $type = $typeResolver->resolve(new \ReflectionProperty(OriginalRooms::class, $key))
                        ->getBaseType()
                        ->getTypeIdentifier()
                        ->value;
                    settype($value, $type);

                    $this->$key = $value;
                }
            }
        }
    }
}
