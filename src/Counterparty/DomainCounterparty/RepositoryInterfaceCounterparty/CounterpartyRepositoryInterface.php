<?php

namespace App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty;

interface  CounterpartyRepositoryInterface
{
    public function save(): array;

    public function number_doubles(array $array): int;
}
