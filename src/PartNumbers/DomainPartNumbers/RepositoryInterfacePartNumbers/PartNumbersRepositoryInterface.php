<?php

namespace App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers;

use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;

interface  PartNumbersRepositoryInterface
{
    public function save(Counterparty $entity_counterparty): array;

    public function edit(): array;

    public function delete(Counterparty $entity_counterparty): array;

    public function numberDoubles(array $array): int;

    public function findAllCounterparty(): ?array;

    public function findOneByCounterparty($name_counterparty): ?array;

    public function findCounterparty($id): ?Counterparty;
}
