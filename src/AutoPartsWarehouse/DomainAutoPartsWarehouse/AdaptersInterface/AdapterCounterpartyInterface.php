<?php

namespace App\AutoPartsWarehouse\DomainAutoPartsWarehouse\AdaptersInterface;


interface AdapterCounterpartyInterface
{
    public function AutoPartsWarehouseDeleteCounterparty(array $arr_counterparty): void;
}
