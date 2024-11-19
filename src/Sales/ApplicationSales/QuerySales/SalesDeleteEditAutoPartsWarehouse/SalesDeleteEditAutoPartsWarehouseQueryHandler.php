<?php

namespace App\Sales\ApplicationSales\QuerySales\SalesDeleteEditAutoPartsWarehouse;

use App\Sales\ApplicationSales\ErrorsSales\InputErrorsSales;
use App\Sales\DomainSales\RepositoryInterfaceSales\AutoPartsSoldRepositoryInterface;
use App\Sales\ApplicationSales\QuerySales\DTOSales\DTOAutoPartsSoldQuery\AutoPartsSoldQuery;

final class SalesDeleteEditAutoPartsWarehouseQueryHandler
{
    public function __construct(
        private InputErrorsSales $inputErrorsSales,
        private AutoPartsSoldRepositoryInterface $autoPartsSoldRepositoryInterface
    ) {}

    public function handler(AutoPartsSoldQuery $autoPartsSoldQuery): void
    {

        $id_auto_parts_warehouse = $autoPartsSoldQuery->getIdAutoPartsWarehouse();
        $this->inputErrorsSales->emptyData($id_auto_parts_warehouse);

        $delete_edit_part_numbers_warehouse = $this->autoPartsSoldRepositoryInterface
            ->findBySalesDeleteEditAutoPartsWarehouse($id_auto_parts_warehouse);
        $this->inputErrorsSales->checkingDataIsTable($delete_edit_part_numbers_warehouse);
    }
}
