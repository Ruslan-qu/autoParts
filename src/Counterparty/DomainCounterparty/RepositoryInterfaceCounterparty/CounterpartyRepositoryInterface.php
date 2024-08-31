<?php

namespace App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty;

use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;

interface  CounterpartyRepositoryInterface
{
    public function save(Counterparty $entity_counterparty): array;

    public function edit(): array;

    public function numberDoubles(array $array): int;

    public function findAllCounterparty(): ?array;

    public function findOneByCounterparty($name_counterparty): ?Counterparty;

    public function findCounterparty($id): ?Counterparty;
}
