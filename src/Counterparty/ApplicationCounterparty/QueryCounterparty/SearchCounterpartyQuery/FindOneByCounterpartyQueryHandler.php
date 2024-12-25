<?php

namespace App\Counterparty\ApplicationCounterparty\QueryCounterparty\SearchCounterpartyQuery;

use App\Counterparty\ApplicationCounterparty\QueryCounterparty\DTOQuery\CounterpartyQuery;
use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;
use App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty\CounterpartyRepositoryInterface;


final class FindOneByCounterpartyQueryHandler
{

    public function __construct(
        private CounterpartyRepositoryInterface $counterpartyRepositoryInterface
    ) {}

    public function handler(CounterpartyQuery $counterpartyQuery): ?Counterparty
    {
        $name_counterparty = strtolower(preg_replace(
            '#\s#u',
            '',
            $counterpartyQuery->getNameCounterparty()
        ));

        $counterparty = $this->counterpartyRepositoryInterface->findOneByIdCounterparty($name_counterparty);

        return $counterparty;
    }
}
