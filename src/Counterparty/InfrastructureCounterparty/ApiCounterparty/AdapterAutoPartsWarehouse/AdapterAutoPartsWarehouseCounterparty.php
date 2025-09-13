<?php

namespace App\Counterparty\InfrastructureCounterparty\ApiCounterparty\AdapterAutoPartsWarehouse;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\Counterparty\ApplicationCounterparty\QueryCounterparty\DTOQuery\CounterpartyQuery;
use App\Counterparty\DomainCounterparty\AdaptersInterface\AdapterAutoPartsWarehouseCounterpartyInterface;
use App\Counterparty\ApplicationCounterparty\QueryCounterparty\SearchCounterpartyQuery\FindByCounterpartyQueryHandler;
use App\Counterparty\ApplicationCounterparty\QueryCounterparty\SearchCounterpartyQuery\SearchCounterpartyQueryHandler;
use App\Counterparty\ApplicationCounterparty\QueryCounterparty\SearchCounterpartyQuery\SearchEmailCounterpartyQueryHandler;

class AdapterAutoPartsWarehouseCounterparty implements AdapterAutoPartsWarehouseCounterpartyInterface
{

    public function __construct(
        private SearchEmailCounterpartyQueryHandler $searchEmailCounterpartyQueryHandler,
        private SearchCounterpartyQueryHandler $searchCounterpartyQueryHandler,
        private FindByCounterpartyQueryHandler $findByCounterpartyQueryHandler
    ) {}

    public function emailCounterpartySearch(array $emails_counterparty): ?array
    {
        $arr_counterparty = [];
        foreach ($emails_counterparty as $key => $value) {

            $counterparty = $this->searchEmailCounterpartyQueryHandler
                ->handler(new CounterpartyQuery($value));

            $arr_counterparty[$key] = $counterparty;
        }

        return $arr_counterparty;
    }

    public function counterpartySearch(array $arr_counterparty): ?array
    {
        $arr_processing_counterparty = [];
        foreach ($arr_counterparty as $key => $value) {

            $counterparty = $this->searchCounterpartyQueryHandler
                ->handler(new CounterpartyQuery($value));

            $arr_processing_counterparty[$key] = ['counterparty' => $counterparty];
        }

        return $arr_processing_counterparty;
    }

    public function findByCounterparty(Participant $participant): ?array
    {
        $id_participant['id_participant'] = $participant;

        $counterparty = $this->findByCounterpartyQueryHandler
            ->handler(new CounterpartyQuery($id_participant));

        return $counterparty;
    }
}
