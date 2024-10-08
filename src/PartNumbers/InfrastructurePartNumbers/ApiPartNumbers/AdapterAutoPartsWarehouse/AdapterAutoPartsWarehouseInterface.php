<?php

namespace App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\AdapterAutoPartsWarehouse;

use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartNumbersFromManufacturers;

interface AdapterAutoPartsWarehouseInterface
{
    public function searchPartNumbersManufacturer(array $arr_part_number_manufactur): ?array;
}
