<?php

namespace App\Counterparty\ApplicationCounterparty\QueryCounterparty\SearchCounterpartyQuery;

use App\Counterparty\ApplicationCounterparty\QueryCounterparty\DTOQuery\CounterpartyQuery;
use App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty\CounterpartyRepositoryInterface;


final class FindByCounterpartyQueryHandler
{

    public function __construct(
        private CounterpartyRepositoryInterface $counterpartyRepositoryInterface
    ) {}

    public function handler(CounterpartyQuery $counterpartyQuery): ?array
    {
        $id_participant = $counterpartyQuery->getIdParticipant();
        $arr_counterparty = $this->counterpartyRepositoryInterface->findByCounterparty($id_participant);

        return $arr_counterparty;
    }
}
