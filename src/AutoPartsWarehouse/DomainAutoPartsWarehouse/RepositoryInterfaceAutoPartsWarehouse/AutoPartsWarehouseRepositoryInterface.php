<?php

namespace App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse;

use DateTimeImmutable;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse;

interface AutoPartsWarehouseRepositoryInterface
{
    public function persistData(AutoPartsWarehouse $autoPartsWarehouse);

    public function flushData($entityManager, $autoPartsWarehouse, $count_key): array;

    public function save(AutoPartsWarehouse $autoPartsWarehouse): int;

    public function edit(AutoPartsWarehouse $autoPartsWarehouse): array;

    public function delete(AutoPartsWarehouse $autoPartsWarehouse): array;

    public function findAllAutoPartsWarehouse(): ?array;

    public function findByAutoPartsWarehouse(array $parameters, string $where): ?array;

    public function findAutoPartsWarehouse(int $id): ?array;

    public function findIdAutoPartsWarehouse(int $id): ?AutoPartsWarehouse;

    public function findCartAutoPartsWarehouse(int $id): ?array;

    public function emptyDateAutoPartsWarehouse(DateTimeImmutable $contentHeadersDate): ?int;

    public function findByShipmentToDate(Participant $id_participant): ?array;
}
