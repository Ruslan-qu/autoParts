<?php

namespace App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\AdapterAutoPartsWarehouse;


interface AdapterAutoPartsWarehouseInterface
{
    public function searchIdDetails(array $arr_part_number): ?array;
}
