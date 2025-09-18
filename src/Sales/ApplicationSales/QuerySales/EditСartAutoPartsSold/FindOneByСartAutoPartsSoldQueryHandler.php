<?php

namespace App\Sales\ApplicationSales\QuerySales\EditСartAutoPartsSold;

use App\Sales\DomainSales\DomainModelSales\AutoPartsSold;
use App\Sales\ApplicationSales\ErrorsSales\InputErrorsSales;
use App\Sales\DomainSales\RepositoryInterfaceSales\AutoPartsSoldRepositoryInterface;
use App\Sales\ApplicationSales\QuerySales\DTOSales\DTOAutoPartsSoldQuery\AutoPartsSoldQuery;


final class FindOneByСartAutoPartsSoldQueryHandler
{
    public function __construct(
        private InputErrorsSales $inputErrorsSales,
        private AutoPartsSoldRepositoryInterface $autoPartsSoldRepositoryInterface
    ) {}

    public function handler(AutoPartsSoldQuery $autoPartsSoldQuery): ?AutoPartsSold
    {

        $id = $autoPartsSoldQuery->getId();
        $this->inputErrorsSales->emptyData($id);
        $id_participant = $autoPartsSoldQuery->getIdParticipant();

        $edit_find_cart_auto_parts_warehouse_sold =
            $this->autoPartsSoldRepositoryInterface->findOneByСartAutoPartsWarehouseSold($id, $id_participant);
        $this->inputErrorsSales->emptyEntity($edit_find_cart_auto_parts_warehouse_sold);

        return $edit_find_cart_auto_parts_warehouse_sold[0];
    }
}
