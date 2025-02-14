<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingApi\DTOCounterpartyAutoParts;

use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingApi\DTOCounterpartyAutoParts\MapArrCounterparty;

final class ArrCounterparty extends MapArrCounterparty

{
    protected ?array $arr_name_counterparty = null;

    public function getArrNameCounterparty(): ?array
    {
        return $this->arr_name_counterparty;
    }
}
