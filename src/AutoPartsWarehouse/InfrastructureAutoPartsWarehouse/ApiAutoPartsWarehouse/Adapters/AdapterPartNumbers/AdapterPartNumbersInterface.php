<?php

namespace App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\Adapters\AdapterPartNumbers;


interface AdapterPartNumbersInterface
{
    public function autoPartsWarehouseDeletePartNumbers(array $data_part_numbers): void;
}
