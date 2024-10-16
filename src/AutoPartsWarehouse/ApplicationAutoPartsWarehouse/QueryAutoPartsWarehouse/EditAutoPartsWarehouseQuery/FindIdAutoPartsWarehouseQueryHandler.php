<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\EditAutoPartsWarehouseQuery;

use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\AutoPartsWarehouseRepositoryInterface;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOAutoPartsWarehouseQuery\AutoPartsWarehouseQuery;


final class FindIdAutoPartsWarehouseQueryHandler
{
    public function __construct(
        private AutoPartsWarehouseRepositoryInterface $autoPartsWarehouseRepositoryInterface
    ) {}

    public function handler(AutoPartsWarehouseQuery $autoPartsWarehouseQuery): ?AutoPartsWarehouse
    {
        //dd($createCounterpartyQuery->getId());
        $id = $autoPartsWarehouseQuery->getId();

        if (empty($id)) {
            return NULL;
        }

        $edit_find_auto_parts_warehouse = $this->autoPartsWarehouseRepositoryInterface->findAutoPartsWarehouse($id);

        return $edit_find_auto_parts_warehouse;
    }
}
