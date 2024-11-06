<?php

namespace App\Counterparty\ApplicationCounterparty\QueryCounterparty\SearchCounterpartyQuery;

use App\Counterparty\ApplicationCounterparty\QueryCounterparty\DTOQuery\CounterpartyQuery;
use App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty\CounterpartyRepositoryInterface;


final class SearchCounterpartyQueryHandler
{

    public function __construct(
        private CounterpartyRepositoryInterface $counterpartyRepositoryInterface
    ) {}

    public function handler(CounterpartyQuery $counterpartyQuery): ?array
    {

        $name_counterparty = strtolower(preg_replace(
            '#\s#',
            '',
            $counterpartyQuery->getNameCounterparty()
        ));

        $counterparty = $this->counterpartyRepositoryInterface->findByCounterparty($name_counterparty);

        return $counterparty;
    }
}
