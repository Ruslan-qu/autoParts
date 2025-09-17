<?php

namespace App\Sales\InfrastructureSales\ApiSales\AdapterAutoPartsWarehouse;

use App\Sales\DomainSales\AdaptersInterface\AdapterAutoPartsWarehouseSalesInterface;
use App\Sales\ApplicationSales\QuerySales\DTOSales\DTOAutoPartsSoldQuery\AutoPartsSoldQuery;
use App\Sales\ApplicationSales\QuerySales\SalesEditAutoPartsWarehouse\SalesEditAutoPartsWarehouseQueryHandler;

class AdapterAutoPartsWarehouseSales implements AdapterAutoPartsWarehouseSalesInterface
{

    public function __construct(
        private SalesEditAutoPartsWarehouseQueryHandler $salesEditAutoPartsWarehouseQueryHandler
    ) {}


    public function salesEditAutoPartsWarehouse(array $data_auto_parts_warehouse): void
    {

        $this->salesEditAutoPartsWarehouseQueryHandler
            ->handler(new AutoPartsSoldQuery($data_auto_parts_warehouse));
    }
}
