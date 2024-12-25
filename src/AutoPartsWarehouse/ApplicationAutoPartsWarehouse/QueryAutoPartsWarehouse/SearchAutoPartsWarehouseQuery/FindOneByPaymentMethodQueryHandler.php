<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\SearchAutoPartsWarehouseQuery;

use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\PaymentMethod;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\PaymentMethodRepositoryInterface;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOPaymentMethodQuery\PaymentMethodQuery;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOPaymentMethodQuery\ArrPaymentMethodQuery;

final class FindOneByPaymentMethodQueryHandler
{

    public function __construct(
        private PaymentMethodRepositoryInterface $paymentMethodRepositoryInterface
    ) {}

    public function handler(ArrPaymentMethodQuery $arrPaymentMethodQuery): ?PaymentMethod
    {
        dd($arrPaymentMethodQuery);
        $method = strtolower(preg_replace(
            '#\s#u',
            '',
            $paymentMethodQuery->getMethod()
        ));

        $counterparty = $this->paymentMethodRepositoryInterface->findOneByPaymentMethod($method);

        return $counterparty;
    }
}
