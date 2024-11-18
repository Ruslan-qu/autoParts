<?php

namespace App\Sales\InfrastructureSales\ApiSales\AdapterAutoPartsWarehouse;

use App\Sales\ApplicationSales\QuerySales\DTOSales\DTOAutoPartsSoldQuery\AutoPartsSoldQuery;
use App\Sales\ApplicationSales\QuerySales\SalesDelete\SalesDeleteAutoPartsWarehouseQueryHandler;
use App\Sales\InfrastructureSales\ApiSales\AdapterAutoPartsWarehouse\AdapterAutoPartsWarehouseInterface;

class AdapterAutoPartsWarehouseSales implements AdapterAutoPartsWarehouseSalesInterface
{

    public function __construct(
        private SalesDeleteAutoPartsWarehouseQueryHandler $salesDeleteAutoPartsWarehouseQueryHandler
    ) {}


    public function salesDeleteAutoPartsWarehouse(array $data_auto_parts_warehouse): void
    {

        $this->salesDeleteAutoPartsWarehouseQueryHandler
            ->handler(new AutoPartsSoldQuery($data_auto_parts_warehouse));
    }
}
