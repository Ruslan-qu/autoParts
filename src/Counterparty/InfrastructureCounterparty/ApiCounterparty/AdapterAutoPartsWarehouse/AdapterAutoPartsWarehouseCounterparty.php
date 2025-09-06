<?php

namespace App\Counterparty\InfrastructureCounterparty\ApiCounterparty\AdapterAutoPartsWarehouse;

use App\Counterparty\ApplicationCounterparty\QueryCounterparty\DTOQuery\CounterpartyQuery;
use App\Counterparty\DomainCounterparty\AdaptersInterface\AdapterAutoPartsWarehouseCounterpartyInterface;
use App\Counterparty\ApplicationCounterparty\QueryCounterparty\SearchCounterpartyQuery\SearchCounterpartyQueryHandler;
use App\Counterparty\ApplicationCounterparty\QueryCounterparty\SearchCounterpartyQuery\FindAllCounterpartyQueryHandler;

class AdapterAutoPartsWarehouseCounterparty implements AdapterAutoPartsWarehouseCounterpartyInterface
{

    public function __construct(
        private SearchCounterpartyQueryHandler $searchCounterpartyQueryHandler,
        private FindAllCounterpartyQueryHandler $findAllCounterpartyQueryHandler
    ) {}

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

    public function allCounterparty(): ?array
    {

        $counterparty = $this->findAllCounterpartyQueryHandler
            ->handler();

        return $counterparty;
    }
}
