<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryPaymentMethod\DeletePaymentMethodQuery;

use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryPaymentMethod\DTOQuery\PaymentMethodQuery;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\PaymentMethod;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\PaymentMethodRepositoryInterface;


final class FindPaymentMethodQueryHandler
{

    public function __construct(
        private InputErrorsAutoPartsWarehouse $inputErrorsAutoPartsWarehouse,
        private PaymentMethodRepositoryInterface $paymentMethodRepositoryInterface
    ) {}

    public function handler(PaymentMethodQuery $paymentMethodQuery): ?PaymentMethod
    {

        $id = $paymentMethodQuery->getId();
        $this->inputErrorsAutoPartsWarehouse->emptyData($id);

        $edit_payment_method = $this->paymentMethodRepositoryInterface->findPaymentMethod($id);
        $this->inputErrorsAutoPartsWarehouse->emptyEntity($edit_payment_method);

        return $edit_payment_method;
    }
}
