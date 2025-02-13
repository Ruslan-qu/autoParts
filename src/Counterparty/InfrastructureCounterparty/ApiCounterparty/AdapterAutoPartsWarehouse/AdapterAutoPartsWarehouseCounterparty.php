<?php

namespace App\Counterparty\InfrastructureCounterparty\ApiCounterparty\AdapterAutoPartsWarehouse;

use App\Counterparty\ApplicationCounterparty\QueryCounterparty\DTOQuery\CounterpartyQuery;
use App\Counterparty\ApplicationCounterparty\QueryCounterparty\SearchCounterpartyQuery\FindAllCounterpartyQueryHandler;
use App\Counterparty\ApplicationCounterparty\QueryCounterparty\SearchCounterpartyQuery\FindOneByCounterpartyQueryHandler;
use App\Counterparty\InfrastructureCounterparty\ApiCounterparty\AdapterAutoPartsWarehouse\AdapterAutoPartsWarehouseCounterpartyInterface;

class AdapterAutoPartsWarehouseCounterparty implements AdapterAutoPartsWarehouseCounterpartyInterface
{

    public function __construct(
        private FindOneByCounterpartyQueryHandler $findOneByCounterpartyQueryHandler,
        private FindAllCounterpartyQueryHandler $findAllCounterpartyQueryHandler
    ) {}

    public function counterpartySearch(array $arr_counterparty): ?array
    {

        foreach ($arr_counterparty as $key => $value) {

            $map_counterparty = ['name_counterparty' => $value['counterparty']];

            $counterparty = $this->findOneByCounterpartyQueryHandler
                ->handler(new CounterpartyQuery($map_counterparty));

            $arr_counterparty[$key] = ['id_counterparty' => $counterparty];
        }

        return $arr_counterparty;
    }

    public function allCounterparty(): ?array
    {

        $counterparty = $this->findAllCounterpartyQueryHandler
            ->handler();

        return $counterparty;
    }
}
