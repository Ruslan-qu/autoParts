<?php

namespace App\Sales\DomainSales\RepositoryInterfaceSales;

use App\Sales\DomainSales\DomainModelSales\AutoPartsSold;

interface AutoPartsSoldRepositoryInterface
{
    public function save(AutoPartsSold $autoPartsSold): array;

    public function edit(array $arr_auto_parts_sold): array;

    public function delete(AutoPartsSold $autoPartsSold): array;

    public function sold(): void;

    public function findIdAutoPartsSold(int $id): ?AutoPartsSold;

    public function findOneByAutoPartsSold(int $id): ?array;

    public function findAutoPartsSold(int $id): ?AutoPartsSold;

    public function findСartAutoPartsWarehouseSold(int $id): ?array;

    public function findOneBySalesAutoParts(int $id): ?array;

    public function findByCartAutoPartsSold(): ?array;

    public function findByCompletionSale(): ?array;

    public function findBySales($arr_parameters, $part_number_where): ?array;

    public function findBySalesToDate(): ?array;

    public function findBySalesDeleteEditAutoPartsWarehouse($id_auto_parts_warehouse): ?array;
}
