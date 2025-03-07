<?php

namespace App\AutoPartsWarehouse\DomainAutoPartsWarehouse\AdaptersInterface;


interface AdapterPartNumbersInterface
{
    public function autoPartsWarehouseDeletePartNumbers(array $data_part_numbers): void;
}
