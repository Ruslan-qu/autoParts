<?php

namespace App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty;

use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;

interface  CounterpartyRepositoryInterface
{
    public function save(Counterparty $entity_counterparty): array;

    public function edit(Counterparty $edit_counterparty): array;

    public function delete(Counterparty $entity_counterparty): array;

    public function numberDoubles(array $array): int;

    public function findAllCounterparty(): ?array;

    public function findByCounterparty($name_counterparty): ?array;

    public function findCounterparty($id): ?Counterparty;
}
