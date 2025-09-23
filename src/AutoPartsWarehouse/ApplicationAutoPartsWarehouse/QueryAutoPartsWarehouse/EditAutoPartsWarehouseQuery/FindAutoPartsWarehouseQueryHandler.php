<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\EditAutoPartsWarehouseQuery;

use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\AutoPartsWarehouseRepositoryInterface;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOAutoPartsWarehouseQuery\AutoPartsWarehouseQuery;


final class FindAutoPartsWarehouseQueryHandler
{
    public function __construct(
        private InputErrorsAutoPartsWarehouse $inputErrorsAutoPartsWarehouse,
        private AutoPartsWarehouseRepositoryInterface $autoPartsWarehouseRepositoryInterface
    ) {}

    public function handler(AutoPartsWarehouseQuery $autoPartsWarehouseQuery): ?AutoPartsWarehouse
    {

        $id = $autoPartsWarehouseQuery->getId();
        $this->inputErrorsAutoPartsWarehouse->emptyData($id);
        $id_participant = $autoPartsWarehouseQuery->getIdParticipant();

        $edit_find_auto_parts_warehouse = $this->autoPartsWarehouseRepositoryInterface
            ->findOneByJoinAutoPartsWarehouse($id, $id_participant);
        $this->inputErrorsAutoPartsWarehouse->emptyEntity($edit_find_auto_parts_warehouse);

        return $edit_find_auto_parts_warehouse[0];
    }
}
