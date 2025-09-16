<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\SearchAutoPartsWarehouseQuery;

use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\AutoPartsWarehouseRepositoryInterface;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOAutoPartsWarehouseQuery\AutoPartsWarehouseQuery;


final class FindOneByAutoPartsWarehouseQueryHandler
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

        $auto_parts_warehouse = $this->autoPartsWarehouseRepositoryInterface->findOneByAutoPartsWarehouse($id, $id_participant);
        $this->inputErrorsAutoPartsWarehouse->emptyEntity($auto_parts_warehouse);

        return $auto_parts_warehouse;
    }
}
