<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\AutoPartsWarehouseDelete;

use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\AutoPartsWarehouseRepositoryInterface;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOAutoPartsWarehouseDeleteQuery\AutoPartsWarehouseDeleteQuery;


final class AutoPartsWarehouseDeletePartNumbersQueryHandler
{
    public function __construct(
        private InputErrorsAutoPartsWarehouse $inputErrorsAutoPartsWarehouse,
        private AutoPartsWarehouseRepositoryInterface $autoPartsWarehouseRepositoryInterface
    ) {}

    public function handler(AutoPartsWarehouseDeleteQuery $autoPartsWarehouseDeleteQuery): void
    {

        $id_details = $autoPartsWarehouseDeleteQuery->getIdDetails();
        $this->inputErrorsAutoPartsWarehouse->emptyData($id_details);

        $delete_part_numbers_warehouse = $this->autoPartsWarehouseRepositoryInterface
            ->findByAutoPartsWarehouseDeletePartNumbers($id_details);
        $this->inputErrorsAutoPartsWarehouse->checkingDataIsTable($delete_part_numbers_warehouse);
    }
}
