<?php

namespace App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty;

use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;

interface  CounterpartyRepositoryInterface
{
    public function save(Counterparty $entity_counterparty): array;

    public function number_doubles(array $array): int;
}
