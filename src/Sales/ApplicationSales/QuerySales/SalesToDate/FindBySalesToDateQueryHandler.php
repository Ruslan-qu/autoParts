<?php

namespace App\Sales\ApplicationSales\QuerySales\SalesToDate;

use App\Sales\DomainSales\RepositoryInterfaceSales\AutoPartsSoldRepositoryInterface;

final class FindBySalesToDateQueryHandler
{
    public function __construct(
        private AutoPartsSoldRepositoryInterface $autoPartsSoldRepositoryInterface
    ) {}

    public function handler(): ?array
    {

        $find_by_sales_to_date = $this->autoPartsSoldRepositoryInterface->findBySalesToDate();

        return $find_by_sales_to_date;
    }
}
