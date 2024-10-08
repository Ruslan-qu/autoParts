<?php

namespace App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\AdapterAutoPartsWarehouse;

use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse;

interface AdapterAutoPartsWarehouseInterface
{
    public function searchPartNumbersManufacturer(array $arr_part_number_manufactur): ?array;
}
