<?php

namespace App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\Adapters\AdapterCounterparty;

use App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\Adapters\AdapterCounterparty\AdapterCounterpartyInterface;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOAutoPartsWarehouseDeleteQuery\AutoPartsWarehouseDeleteQuery;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\AutoPartsWarehouseDelete\AutoPartsWarehouseDeleteCounterpartyQueryHandler;

class AdapterCounterparty implements AdapterCounterpartyInterface
{

    public function __construct(
        private AutoPartsWarehouseDeleteCounterpartyQueryHandler $autoPartsWarehouseDeleteCounterpartyQueryHandler
    ) {}


    public function AutoPartsWarehouseDeleteCounterparty(array $arr_counterparty): void
    {

        $this->autoPartsWarehouseDeleteCounterpartyQueryHandler
            ->handler(new AutoPartsWarehouseDeleteQuery($arr_counterparty));
    }
}
