<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\SearchAutoPartsWarehouseQuery;

use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\AutoPartsWarehouseRepositoryInterface;


final class FindAllAutoPartsWarehouseQueryHandler
{

    public function __construct(
        private AutoPartsWarehouseRepositoryInterface $autoPartsWarehouseRepositoryInterface
    ) {}

    public function handler(): ?array
    {

        $paymentMethod = $this->autoPartsWarehouseRepositoryInterface->findAllAutoPartsWarehouse();

        return $paymentMethod;
    }
}
