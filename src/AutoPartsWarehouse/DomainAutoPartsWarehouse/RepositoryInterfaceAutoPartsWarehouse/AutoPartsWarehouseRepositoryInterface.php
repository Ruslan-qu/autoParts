<?php

namespace App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse;

use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use ContainerHt7Qm2w\EntityManagerGhost614a58f;
use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartNumbersFromManufacturers;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse;

interface AutoPartsWarehouseRepositoryInterface
{
    public function persistData(AutoPartsWarehouse $autoPartsWarehouse): EntityManagerGhost614a58f;

    public function flushData(EntityManagerGhost614a58f $entityManager): array;

    public function save(AutoPartsWarehouse $autoPartsWarehouse): array;

    public function edit(AutoPartsWarehouse $autoPartsWarehouse): array;

    public function delete(AutoPartsWarehouse $autoPartsWarehouse): array;

    public function findByAutoPartsWarehouse(array $parameters, string $where): ?array;

    public function findAutoPartsWarehouse(int $id): ?array;

    public function findIdAutoPartsWarehouse(int $id): ?AutoPartsWarehouse;

    public function findCartAutoPartsWarehouse(int $id): ?array;

    public function findByAutoPartsWarehouseDeletePartNumbers(PartNumbersFromManufacturers $find_part_numbers): ?array;

    public function findByAutoPartsWarehouseDeleteCounterparty(Counterparty $delete_counterparty): ?array;
}
