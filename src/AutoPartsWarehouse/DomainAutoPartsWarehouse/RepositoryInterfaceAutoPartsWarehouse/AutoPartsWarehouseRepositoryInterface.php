<?php

namespace App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse;

use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse;

interface AutoPartsWarehouseRepositoryInterface
{
    public function save(AutoPartsWarehouse $autoPartsWarehouse): array;

    /* public function edit(array $arr_edit_part_number): array;

    public function delete(PartNumbersFromManufacturers $partNumbersFromManufacturers): ?array;

    public function numberDoubles(array $array): int;*/

    public function findByAutoPartsWarehouse(array $parameters, string $where): ?array;

    public function findAutoPartsWarehouse(int $id): ?AutoPartsWarehouse;
}
