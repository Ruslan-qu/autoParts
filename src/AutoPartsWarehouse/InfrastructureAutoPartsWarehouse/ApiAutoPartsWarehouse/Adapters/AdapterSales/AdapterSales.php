<?php

namespace App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\Adapters\AdapterSales;

use App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\Adapters\AdapterSales\AdapterSalesInterface;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOAutoPartsWarehouseQuery\AutoPartsWarehouseQuery;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\CartAutoPartsWarehouseSoldQuery\FindCartAutoPartsWarehouseQueryHandler;

class AdapterSales implements AdapterSalesInterface
{

    public function __construct(
        private FindCartAutoPartsWarehouseQueryHandler $findCartAutoPartsWarehouseQueryHandler
    ) {}

    public function findCartPartsWarehouse(array $arr_part_number): ?array
    {

        $car_parts_for_sale = $this->findCartAutoPartsWarehouseQueryHandler
            ->handler(new AutoPartsWarehouseQuery($arr_part_number));

        return $car_parts_for_sale;
    }
}
