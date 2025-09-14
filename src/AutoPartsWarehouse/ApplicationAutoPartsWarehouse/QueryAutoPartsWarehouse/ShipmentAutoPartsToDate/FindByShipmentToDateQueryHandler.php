<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\ShipmentAutoPartsToDate;

use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\AutoPartsWarehouseRepositoryInterface;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOAutoPartsWarehouseQuery\AutoPartsWarehouseQuery;

final class FindByShipmentToDateQueryHandler
{
    public function __construct(
        private AutoPartsWarehouseRepositoryInterface $autoPartsWarehouseRepositoryInterface
    ) {}

    public function handler(AutoPartsWarehouseQuery $autoPartsWarehouseQuery): ?array
    {
        $id_participant = $autoPartsWarehouseQuery->getIdParticipant();

        $find_by_shipment_to_date = $this->autoPartsWarehouseRepositoryInterface->findByShipmentToDate($id_participant);

        return $find_by_shipment_to_date;
    }
}
