<?php

namespace App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\Adapters\AdapterSales;

use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\AdaptersInterface\AdapterSalesInterface;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOAutoPartsWarehouseQuery\AutoPartsWarehouseQuery;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\CartAutoPartsWarehouseSoldQuery\FindOneByCartAutoPartsWarehouseQueryHandler;

class AdapterSales implements AdapterSalesInterface
{

    public function __construct(
        private FindOneByCartAutoPartsWarehouseQueryHandler $findOneByCartAutoPartsWarehouseQueryHandler
    ) {}

    public function findOneByCartPartsWarehouse(array $autoPartsWarehouse): ?array
    {

        $car_parts_for_sale = $this->findOneByCartAutoPartsWarehouseQueryHandler
            ->handler(new AutoPartsWarehouseQuery($autoPartsWarehouse));

        return $car_parts_for_sale;
    }
}
