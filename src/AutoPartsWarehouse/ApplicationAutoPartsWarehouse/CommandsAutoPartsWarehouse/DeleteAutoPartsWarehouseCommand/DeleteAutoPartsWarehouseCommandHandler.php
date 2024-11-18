<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DeleteAutoPartsWarehouseCommand;

use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\AutoPartsWarehouseRepositoryInterface;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DTOCommands\DTOAutoPartsWarehouseCommand\AutoPartsWarehouseCommand;

final class DeleteAutoPartsWarehouseCommandHandler
{
    public function __construct(
        private InputErrorsAutoPartsWarehouse $inputErrorsAutoPartsWarehouse,
        private AutoPartsWarehouseRepositoryInterface $autoPartsWarehouseRepositoryInterface
    ) {}

    public function handler(AutoPartsWarehouseCommand $autoPartsWarehouseCommand): ?int
    {

        $id = $autoPartsWarehouseCommand->getId();
        $this->inputErrorsAutoPartsWarehouse->emptyData($id);

        $find_auto_parts_warehouse = $this->autoPartsWarehouseRepositoryInterface->findIdAutoPartsWarehouse($id);
        $this->inputErrorsAutoPartsWarehouse->emptyEntity($find_auto_parts_warehouse);

        $successfully_delete = $this->autoPartsWarehouseRepositoryInterface->delete($find_auto_parts_warehouse);


        return $successfully_delete['delete'];
    }
}
