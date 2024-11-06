<?php

namespace App\Counterparty\ApplicationCounterparty\QueryCounterparty\SearchCounterpartyQuery;

use App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty\CounterpartyRepositoryInterface;


final class FindByCounterpartyQueryHandler
{

    public function __construct(
        private CounterpartyRepositoryInterface $counterpartyRepositoryInterface
    ) {}

    public function handler(): ?array
    {

        $counterparty = $this->counterpartyRepositoryInterface->findByCounterparty();

        return $counterparty;
    }
}
