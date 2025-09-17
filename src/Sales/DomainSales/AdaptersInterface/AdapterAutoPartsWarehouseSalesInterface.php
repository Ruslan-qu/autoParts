<?php

namespace App\Sales\DomainSales\AdaptersInterface;


interface AdapterAutoPartsWarehouseSalesInterface
{
    public function salesEditAutoPartsWarehouse(array $data_auto_parts_warehouse): void;
}
