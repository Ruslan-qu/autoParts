<?php

namespace App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\Adapters\AdapterSales;


interface AdapterSalesInterface
{
    public function findCartPartsWarehouse(array $arr_part_number): ?array;
}
