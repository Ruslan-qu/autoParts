<?php

namespace App\Counterparty\ApplicationCounterparty\QueryCounterparty\SearchCounterpartyQuery;

use App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty\CounterpartyRepositoryInterface;


final class FindAllCounterpartyQueryHandler
{

    public function __construct(
        private CounterpartyRepositoryInterface $counterpartyRepositoryInterface
    ) {}

    public function handler(): ?array
    {

        $counterparty = $this->counterpartyRepositoryInterface->findAllCounterparty();

        return $counterparty;
    }
}
