<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\SearchAutoPartsWarehouseQuery;

use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\PaymentMethodRepositoryInterface;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOPaymentMethodQuery\ArrPaymentMethodQuery;

final class FindOneByPaymentMethodQueryHandler
{

    public function __construct(
        private PaymentMethodRepositoryInterface $paymentMethodRepositoryInterface
    ) {}

    public function handler(ArrPaymentMethodQuery $arrPaymentMethodQuery): ?array
    {
        $arr_method = [];
        foreach ($arrPaymentMethodQuery->getArrMethod() as $key => $value) {

            $id = $value['method']->getId();
            $payment_method = $this->paymentMethodRepositoryInterface->findOneByPaymentMethod($id);
            $arr_method[$key] = ['id_payment_method' => $payment_method];
        }

        return $arr_method;
    }
}
