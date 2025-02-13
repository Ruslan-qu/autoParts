<?php

namespace App\Counterparty\InfrastructureCounterparty\ApiCounterparty\AdapterAutoPartsWarehouse;



interface AdapterAutoPartsWarehouseCounterpartyInterface
{
    public function counterpartySearch(array $arr_counterparty): ?array;

    public function allCounterparty(): ?array;
}
