<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\CartAutoPartsWarehouseSoldQuery;

use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\AutoPartsSoldRepositoryInterface;

final class FindByCartAutoPartsSoldQueryHandler
{
    public function __construct(
        private AutoPartsSoldRepositoryInterface $autoPartsSoldRepositoryInterface
    ) {}

    public function handler(): ?array
    {

        $find_cart_auto_parts_warehouse = $this->autoPartsSoldRepositoryInterface->findByCartAutoPartsSold();

        return $find_cart_auto_parts_warehouse;
    }
}
