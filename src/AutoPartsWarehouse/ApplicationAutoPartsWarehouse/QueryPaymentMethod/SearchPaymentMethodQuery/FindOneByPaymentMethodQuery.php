<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryPaymentMethod\SearchPaymentMethodQuery;

use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryPaymentMethod\DTOQuery\PaymentMethodQuery;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryPaymentMethod\SearchPaymentMethodQuery\SearchPaymentMethodQueryHandler;

final class FindOneByPaymentMethodQuery
{

    public function __construct(
        private SearchPaymentMethodQueryHandler $searchPaymentMethodQueryHandler,

    ) {}

    public function handler(?array $arrPaymentMethod): ?array
    {
        $arr_method = [];
        foreach ($arrPaymentMethod as $key => $value) {

            $payment_method = $this->searchPaymentMethodQueryHandler
                ->handler(new PaymentMethodQuery($value));
            $arr_method[$key] = ['payment_method' => $payment_method];
        }

        return $arr_method;
    }
}
