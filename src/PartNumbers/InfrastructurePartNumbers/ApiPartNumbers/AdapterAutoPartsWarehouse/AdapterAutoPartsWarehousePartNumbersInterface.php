<?php

namespace App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\AdapterAutoPartsWarehouse;

use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartNumbersFromManufacturers;


interface AdapterAutoPartsWarehousePartNumbersInterface
{
    public function searchIdDetails(array $arr_part_number): ?PartNumbersFromManufacturers;
}
