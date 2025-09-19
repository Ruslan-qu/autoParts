<?php

namespace App\Sales\ApplicationSales\QuerySales\SalesToDate;

use App\Sales\ApplicationSales\QuerySales\DTOSales\DTOSalesQuery\SalesQuery;
use App\Sales\DomainSales\RepositoryInterfaceSales\AutoPartsSoldRepositoryInterface;

final class FindBySalesToDateQueryHandler
{
    public function __construct(
        private AutoPartsSoldRepositoryInterface $autoPartsSoldRepositoryInterface
    ) {}

    public function handler(SalesQuery $salesQuery): ?array
    {

        $id_participant = $salesQuery->getIdParticipant();

        $find_by_sales_to_date = $this->autoPartsSoldRepositoryInterface->findBySalesToDate($id_participant);

        return $find_by_sales_to_date;
    }
}
