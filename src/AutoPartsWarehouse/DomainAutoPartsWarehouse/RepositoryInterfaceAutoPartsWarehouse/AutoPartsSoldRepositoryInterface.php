<?php

namespace App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse;

use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DTOCommands\DTOAutoPartsSoldCommand\AutoPartsSoldCommand;

interface AutoPartsSoldRepositoryInterface
{
    public function save(AutoPartsSoldCommand $autoPartsSoldCommand): array;

    /*public function edit(AutoPartsWarehouse $autoPartsWarehouse): array;

    public function delete(AutoPartsWarehouse $autoPartsWarehouse): array;

   public function numberDoubles(array $array): int;

    public function findByAutoPartsWarehouse(array $parameters, string $where): ?array;

    public function findAutoPartsWarehouse(int $id): ?array;

    public function findIdAutoPartsWarehouse(int $id): ?AutoPartsWarehouse;

    public function findCartAutoPartsWarehouse(int $id): ?array;*/
}
