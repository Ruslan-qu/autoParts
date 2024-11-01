<?php

namespace App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\AdapterSales;

use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartNumbersFromManufacturers;

interface AdapterSalesInterface
{
    public function findCartPartsWarehouse(array $arr_part_number): ?array;
}
