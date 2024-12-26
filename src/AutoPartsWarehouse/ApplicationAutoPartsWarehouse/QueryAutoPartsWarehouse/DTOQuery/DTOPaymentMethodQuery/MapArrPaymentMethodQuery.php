<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOPaymentMethodQuery;

use Symfony\Component\TypeInfo\TypeResolver\TypeResolver;
use App\Counterparty\ApplicationCounterparty\Errors\InputErrors;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOPaymentMethodQuery\PaymentMethodQuery;

abstract class MapArrPaymentMethodQuery
{

    public function __construct(array $data = [])
    {
        $this->load($data);
    }

    private function load(array $data)
    {
        foreach ($data as $key => $value) {
            $arr_method[$key] = ['method' => new PaymentMethodQuery($value)];
        }
        $this->arr_method = (array)$arr_method;
    }
}
