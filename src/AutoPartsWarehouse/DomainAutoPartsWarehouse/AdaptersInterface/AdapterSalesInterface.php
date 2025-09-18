<?php

namespace App\AutoPartsWarehouse\DomainAutoPartsWarehouse\AdaptersInterface;


interface AdapterSalesInterface
{
    public function findOneByCartPartsWarehouse(array $autoPartsWarehouse): ?array;
}
