<?php

namespace App\Sales\ApplicationSales\QuerySales\ListCartAutoParts;

use App\Sales\DomainSales\RepositoryInterfaceSales\AutoPartsSoldRepositoryInterface;

final class FindByCartAutoPartsSoldQueryHandler
{
    public function __construct(
        private AutoPartsSoldRepositoryInterface $autoPartsSoldRepositoryInterface
    ) {}

    public function handler(): ?array
    {

        $find_cart_auto_parts_sold = $this->autoPartsSoldRepositoryInterface->findByCartAutoPartsSold();

        return $find_cart_auto_parts_sold;
    }
}
