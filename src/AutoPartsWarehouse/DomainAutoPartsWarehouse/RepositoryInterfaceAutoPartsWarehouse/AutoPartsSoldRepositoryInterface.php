<?php

namespace App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse;

use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsSold;

interface AutoPartsSoldRepositoryInterface
{
    public function save(AutoPartsSold $autoPartsSold): array;

    public function edit(array $arr_auto_parts_sold): array;

    public function delete(AutoPartsSold $autoPartsSold): array;

    public function findIdAutoPartsSold(int $id): ?AutoPartsSold;

    public function findAutoPartsSold(int $id): ?AutoPartsSold;

    public function findСartAutoPartsWarehouseSold(int $id): ?array;

    public function findByCartAutoPartsSold(): ?array;
}
