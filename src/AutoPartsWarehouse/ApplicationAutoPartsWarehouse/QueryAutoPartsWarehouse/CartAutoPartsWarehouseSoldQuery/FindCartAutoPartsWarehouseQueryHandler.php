<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\CartAutoPartsWarehouseSoldQuery;

use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\AutoPartsWarehouseRepositoryInterface;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOAutoPartsWarehouseQuery\AutoPartsWarehouseQuery;


final class FindCartAutoPartsWarehouseQueryHandler
{
    public function __construct(
        private InputErrorsAutoPartsWarehouse $inputErrorsAutoPartsWarehouse,
        private AutoPartsWarehouseRepositoryInterface $autoPartsWarehouseRepositoryInterface
    ) {}

    public function handler(AutoPartsWarehouseQuery $autoPartsWarehouseQuery): ?array
    {

        $id = $autoPartsWarehouseQuery->getId();
        $this->inputErrorsAutoPartsWarehouse->emptyData($id);

        $find_cart_auto_parts_warehouse = $this->autoPartsWarehouseRepositoryInterface->findCartAutoPartsWarehouse($id);
        $this->inputErrorsAutoPartsWarehouse->emptyEntity($find_cart_auto_parts_warehouse);

        return $find_cart_auto_parts_warehouse;
    }
}
