<?php

namespace App\Counterparty\ApplicationCounterparty\QueryCounterparty\EditCounterpartyQuery;

use App\Counterparty\ApplicationCounterparty\Errors\InputErrors;
use App\Counterparty\ApplicationCounterparty\QueryCounterparty\DTOQuery\CounterpartyQuery;
use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;
use App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty\CounterpartyRepositoryInterface;


final class FindIdCounterpartyQueryHandler
{

    public function __construct(
        private InputErrors $inputErrors,
        private CounterpartyRepositoryInterface $counterpartyRepositoryInterface
    ) {}

    public function handler(CounterpartyQuery $counterpartyQuery): ?Counterparty
    {

        $id = $counterpartyQuery->getId();
        $this->inputErrors->emptyData($id);

        $edit_counterparty = $this->counterpartyRepositoryInterface->findCounterparty($id);
        $this->inputErrors->emptyEntity($edit_counterparty);

        return $edit_counterparty;
    }
}
