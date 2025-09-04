<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsPaymentMethod\DeletePaymentMethodCommand;

use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\PaymentMethodRepositoryInterface;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsPaymentMethod\DTOCommands\DTOPaymentMethodObjCommand\PaymentMethodObjCommand;

final class DeletePaymentMethodCommandHandler
{

    public function __construct(
        private PaymentMethodRepositoryInterface $paymentMethodRepositoryInterface,
    ) {}

    public function handler(PaymentMethodObjCommand $paymentMethodObjCommand): int
    {
        $counterparty = $paymentMethodObjCommand->getPaymentMethod();

        return $this->paymentMethodRepositoryInterface->delete($counterparty);
    }
}
