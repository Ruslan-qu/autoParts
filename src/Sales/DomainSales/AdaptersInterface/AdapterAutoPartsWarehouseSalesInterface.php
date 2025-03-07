<?php

namespace App\Sales\DomainSales\AdaptersInterface;


interface AdapterAutoPartsWarehouseSalesInterface
{
    public function salesDeleteEditAutoPartsWarehouse(array $data_auto_parts_warehouse): void;
}
