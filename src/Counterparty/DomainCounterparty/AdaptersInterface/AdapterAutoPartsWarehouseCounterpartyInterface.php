<?php

namespace App\Counterparty\DomainCounterparty\AdaptersInterface;



interface AdapterAutoPartsWarehouseCounterpartyInterface
{
    public function counterpartySearch(array $arr_counterparty): ?array;

    public function allCounterparty(): ?array;
}
