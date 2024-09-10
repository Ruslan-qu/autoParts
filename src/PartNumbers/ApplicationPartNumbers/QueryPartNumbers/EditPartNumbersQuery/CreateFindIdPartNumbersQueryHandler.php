<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\EditPartNumbersQuery;

use App\Counterparty\ApplicationCounterparty\QueryCounterparty\DTOQuery\CreateCounterpartyQuery;
use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;
use App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty\CounterpartyRepositoryInterface;


final class CreateFindIdPartNumbersQueryHandler
{
    private $counterparty_repository_interface;

    public function __construct(
        CounterpartyRepositoryInterface $counterparty_repository_interface
    ) {
        $this->counterparty_repository_interface = $counterparty_repository_interface;
    }

    public function handler(CreateCounterpartyQuery $createCounterpartyQuery): ?Counterparty
    {
        //dd($createCounterpartyQuery->getId());
        $id = $createCounterpartyQuery->getId();

        if (empty($id)) {
            return NULL;
        }

        $edit_counterparty = $this->counterparty_repository_interface->findCounterparty($id);

        return $edit_counterparty;
    }
}
