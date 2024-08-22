<?php

namespace App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty;

use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;

interface  CounterpartyRepositoryInterface
{
    public function save(Counterparty $entity_counterparty): array;

    public function numberDoubles(array $array): int;

    public function findAllCounterparty(): array;
}
