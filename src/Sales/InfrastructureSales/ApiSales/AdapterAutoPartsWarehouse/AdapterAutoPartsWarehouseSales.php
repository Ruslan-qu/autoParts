<?php

namespace App\Sales\InfrastructureSales\ApiSales\AdapterAutoPartsWarehouse;

use App\Sales\ApplicationSales\QuerySales\DTOSales\DTOAutoPartsSoldQuery\AutoPartsSoldQuery;
use App\Sales\ApplicationSales\QuerySales\SalesDeleteEditAutoPartsWarehouse\SalesDeleteEditAutoPartsWarehouseQueryHandler;

class AdapterAutoPartsWarehouseSales implements AdapterAutoPartsWarehouseSalesInterface
{

    public function __construct(
        private SalesDeleteEditAutoPartsWarehouseQueryHandler $salesDeleteEditAutoPartsWarehouseQueryHandler
    ) {}


    public function salesDeleteEditAutoPartsWarehouse(array $data_auto_parts_warehouse): void
    {

        $this->salesDeleteEditAutoPartsWarehouseQueryHandler
            ->handler(new AutoPartsSoldQuery($data_auto_parts_warehouse));
    }
}
