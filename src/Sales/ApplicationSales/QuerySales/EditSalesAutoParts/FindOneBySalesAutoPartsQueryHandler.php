<?php

namespace App\Sales\ApplicationSales\QuerySales\EditSalesAutoParts;

use App\Sales\DomainSales\DomainModelSales\AutoPartsSold;
use App\Sales\ApplicationSales\ErrorsSales\InputErrorsSales;
use App\Sales\DomainSales\RepositoryInterfaceSales\AutoPartsSoldRepositoryInterface;
use App\Sales\ApplicationSales\QuerySales\DTOSales\DTOAutoPartsSoldQuery\AutoPartsSoldQuery;


final class FindOneBySalesAutoPartsQueryHandler
{
    public function __construct(
        private InputErrorsSales $inputErrorsSales,
        private AutoPartsSoldRepositoryInterface $autoPartsSoldRepositoryInterface
    ) {}

    public function handler(AutoPartsSoldQuery $autoPartsSoldQuery): ?AutoPartsSold
    {

        $id = $autoPartsSoldQuery->getId();
        $this->inputErrorsSales->emptyData($id);

        $edit_find_one_by_sales_auto_parts = $this->autoPartsSoldRepositoryInterface->findOneBySalesAutoParts($id);
        $this->inputErrorsSales->emptyEntity($edit_find_one_by_sales_auto_parts);

        return $edit_find_one_by_sales_auto_parts[0];
    }
}
