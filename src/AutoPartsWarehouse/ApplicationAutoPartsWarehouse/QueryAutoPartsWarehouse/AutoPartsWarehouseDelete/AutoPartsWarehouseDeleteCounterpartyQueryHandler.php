<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\AutoPartsWarehouseDelete;

use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\AutoPartsWarehouseRepositoryInterface;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOAutoPartsWarehouseDeleteQuery\AutoPartsWarehouseDeleteQuery;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOAutoPartsWarehouseDeleteCounterpartyQuery\AutoPartsWarehouseDeleteCounterpartyQuery;


final class AutoPartsWarehouseDeleteCounterpartyQueryHandler
{
    public function __construct(
        private InputErrorsAutoPartsWarehouse $inputErrorsAutoPartsWarehouse,
        private AutoPartsWarehouseRepositoryInterface $autoPartsWarehouseRepositoryInterface
    ) {}

    public function handler(AutoPartsWarehouseDeleteQuery $autoPartsWarehouseDeleteQuery): void
    {

        $id_counterparty = $autoPartsWarehouseDeleteQuery->getIdCounterparty();
        $this->inputErrorsAutoPartsWarehouse->emptyData($id_counterparty);

        $delete_part_numbers_warehouse = $this->autoPartsWarehouseRepositoryInterface
            ->findByAutoPartsWarehouseDeleteCounterparty($id_counterparty);
        $this->inputErrorsAutoPartsWarehouse->checkingDataIsTable($delete_part_numbers_warehouse);
    }
}
