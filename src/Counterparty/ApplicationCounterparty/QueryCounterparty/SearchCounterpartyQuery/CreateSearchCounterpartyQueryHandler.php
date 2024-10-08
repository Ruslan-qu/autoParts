<?php

namespace App\Counterparty\ApplicationCounterparty\QueryCounterparty\SearchCounterpartyQuery;

use App\Counterparty\ApplicationCounterparty\QueryCounterparty\DTOQuery\CreateCounterpartyQuery;
use App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty\CounterpartyRepositoryInterface;


final class CreateSearchCounterpartyQueryHandler
{
    private $counterparty_repository_interface;

    public function __construct(
        CounterpartyRepositoryInterface $counterparty_repository_interface
    ) {
        $this->counterparty_repository_interface = $counterparty_repository_interface;
    }

    public function handler(CreateCounterpartyQuery $createCounterpartyQuery): ?array
    {

        $name_counterparty = strtolower(preg_replace(
            '#\s#',
            '',
            $createCounterpartyQuery->getNameCounterparty()
        ));

        $counterparty = $this->counterparty_repository_interface->findByCounterparty($name_counterparty);

        return $counterparty;
    }
}
