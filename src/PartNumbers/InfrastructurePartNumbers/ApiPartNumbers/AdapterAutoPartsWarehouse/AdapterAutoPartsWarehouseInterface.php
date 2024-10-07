<?php

namespace App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse;

use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse;

interface AdapterAutoPartsWarehouseInterface
{
    public function searchPartNumbersManufacturer(array $arr_part_numbers_manufacturer): ?array;
}
