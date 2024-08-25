<?php

namespace App\Counterparty\ApplicationCounterparty\QueryCounterparty\SearchCounterpartyQuery;

use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;
use App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty\CounterpartyRepositoryInterface;


final class CreateSearchCounterpartyQueryHandler
{
    private $counterparty_repository_interface;

    public function __construct(
        CounterpartyRepositoryInterface $counterparty_repository_interface
    ) {
        $this->counterparty_repository_interface = $counterparty_repository_interface;
    }

    public function handler(CreateSearchCounterpartyQuery $createSearchCounterpartyQuery): ?Counterparty
    {

        $name_counterparty = strtolower(preg_replace(
            '#\s#',
            '',
            $createSearchCounterpartyQuery->getNameCounterparty()
        ));

        $counterparty = $this->counterparty_repository_interface->findOneByCounterparty($name_counterparty);

        return $counterparty;
    }
}
