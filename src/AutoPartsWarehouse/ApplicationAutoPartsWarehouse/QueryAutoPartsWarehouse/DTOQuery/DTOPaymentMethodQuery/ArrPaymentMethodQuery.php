<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOPaymentMethodQuery;

use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOPaymentMethodQuery\MapArrPaymentMethodQuery;

final class ArrPaymentMethodQuery extends MapArrPaymentMethodQuery
{
    protected ?array $arr_method = null;

    public function getArrMethod(): ?array
    {
        return $this->arr_method;
    }
}
