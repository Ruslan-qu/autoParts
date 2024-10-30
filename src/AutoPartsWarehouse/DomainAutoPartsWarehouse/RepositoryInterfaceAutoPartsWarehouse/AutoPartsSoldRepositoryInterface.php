<?php

namespace App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse;

use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsSold;

interface AutoPartsSoldRepositoryInterface
{
    public function save(AutoPartsSold $autoPartsSold): array;

    /*public function edit(AutoPartsWarehouse $autoPartsWarehouse): array;

    public function delete(AutoPartsWarehouse $autoPartsWarehouse): array;

   public function numberDoubles(array $array): int;

    public function findByAutoPartsWarehouse(array $parameters, string $where): ?array;*/

    /*public function findAutoPartsWarehouse(int $id): ?array;*/

    public function findСartAutoPartsWarehouseSold(int $id): ?array;

    public function findByCartAutoPartsSold(): ?array;
}
