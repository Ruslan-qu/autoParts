<?php

namespace App\AutoPartsWarehouse\DomainAutoPartsWarehouse\AdaptersInterface;


interface AdapterSalesInterface
{
    public function findCartPartsWarehouse(array $arr_part_number): ?array;
}
