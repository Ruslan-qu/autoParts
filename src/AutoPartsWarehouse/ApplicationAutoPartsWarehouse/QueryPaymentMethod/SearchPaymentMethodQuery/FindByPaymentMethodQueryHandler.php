<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryPaymentMethod\SearchPaymentMethodQuery;

use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryPaymentMethod\DTOQuery\PaymentMethodQuery;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\PaymentMethodRepositoryInterface;


final class FindByPaymentMethodQueryHandler
{

    public function __construct(
        private InputErrorsAutoPartsWarehouse $inputErrorsAutoPartsWarehouse,
        private PaymentMethodRepositoryInterface $paymentMethodRepositoryInterface
    ) {}

    public function handler(PaymentMethodQuery $paymentMethodQuery): ?array
    {
        $id_participant = $paymentMethodQuery->getIdParticipant();
        $this->inputErrorsAutoPartsWarehouse->userIsNotIdentified($id_participant);

        $arr_payment_method = $this->paymentMethodRepositoryInterface->findByPaymentMethod($id_participant);

        return $arr_payment_method;
    }
}
