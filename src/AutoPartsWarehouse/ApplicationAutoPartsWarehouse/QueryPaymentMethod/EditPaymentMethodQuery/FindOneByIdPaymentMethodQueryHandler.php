<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryPaymentMethod\EditPaymentMethodQuery;

use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryPaymentMethod\DTOQuery\PaymentMethodQuery;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\PaymentMethod;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\PaymentMethodRepositoryInterface;

final class FindOneByIdPaymentMethodQueryHandler
{

    public function __construct(
        private InputErrorsAutoPartsWarehouse $inputErrorsAutoPartsWarehouse,
        private PaymentMethodRepositoryInterface $paymentMethodRepositoryInterface
    ) {}

    public function handler(PaymentMethodQuery $paymentMethodQuery): ?PaymentMethod
    {
        $id = $paymentMethodQuery->getId();
        $this->inputErrorsAutoPartsWarehouse->emptyData($id);

        $participant = $paymentMethodQuery->getIdParticipant();
        $this->inputErrorsAutoPartsWarehouse->userIsNotIdentified($participant);

        $edit_counterparty = $this->paymentMethodRepositoryInterface->findOneByIdPaymentMethod($id, $participant);
        $this->inputErrorsAutoPartsWarehouse->emptyEntity($edit_counterparty);

        return $edit_counterparty;
    }
}
