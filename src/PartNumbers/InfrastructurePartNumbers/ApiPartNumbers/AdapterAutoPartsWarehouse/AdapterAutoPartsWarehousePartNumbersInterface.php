<?php

namespace App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\AdapterAutoPartsWarehouse;

use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartNumbersFromManufacturers;


interface AdapterAutoPartsWarehousePartNumbersInterface
{
    public function searchIdDetails(array $arr_part_number): ?PartNumbersFromManufacturers;

    public function partNumberSearch(array $arr_part_number): ?array;

    public function idPartNumbersSearch(array $arr_part_number): ?array;
}
