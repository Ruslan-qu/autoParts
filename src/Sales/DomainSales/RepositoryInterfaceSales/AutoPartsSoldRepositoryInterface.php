<?php

namespace App\Sales\DomainSales\RepositoryInterfaceSales;

use App\Sales\DomainSales\DomainModelSales\AutoPartsSold;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;

interface AutoPartsSoldRepositoryInterface
{
    public function save(AutoPartsSold $autoPartsSold): int;

    public function edit(AutoPartsSold $autoPartsSold): int;

    public function delete(AutoPartsSold $autoPartsSold): int;

    public function sold(): void;

    public function findIdAutoPartsSold(int $id): ?AutoPartsSold;

    public function findOneByJoinAutoPartsSold(int $id): ?array;

    public function findOneByAutoPartsSold(int $id, Participant $id_participant): ?AutoPartsSold;

    public function findOneByСartAutoPartsWarehouseSold(int $id, Participant $id_participant): ?array;

    public function findOneBySalesAutoParts(int $id, Participant $id_participant): ?array;

    public function findByCartAutoPartsSold(): ?array;

    public function findByCompletionSale(): ?array;

    public function findBySales($arr_parameters, $part_number_where): ?array;

    public function findBySalesToDate(): ?array;

    public function findBySalesEditAutoPartsWarehouse($id_auto_parts_warehouse): ?array;
}
