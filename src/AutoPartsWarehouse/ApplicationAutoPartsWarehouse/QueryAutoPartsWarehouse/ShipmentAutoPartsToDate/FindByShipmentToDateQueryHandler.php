<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\ShipmentAutoPartsToDate;

use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\AutoPartsWarehouseRepositoryInterface;

final class FindByShipmentToDateQueryHandler
{
    public function __construct(
        private AutoPartsWarehouseRepositoryInterface $autoPartsWarehouseRepositoryInterface
    ) {}

    public function handler(): ?array
    {

        $find_by_shipment_to_date = $this->autoPartsWarehouseRepositoryInterface->findByShipmentToDate();

        return $find_by_shipment_to_date;
    }
}
