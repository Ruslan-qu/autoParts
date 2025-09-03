<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsPaymentMethod\DTOCommands\DTOPaymentMethodObjCommand;

use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\PaymentMethod;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsPaymentMethod\DTOCommands\DTOPaymentMethodObjCommand\MapPaymentMethodObjCommand;

final class PaymentMethodObjCommand extends MapPaymentMethodObjCommand
{
    protected ?PaymentMethod $payment_method = null;

    public function getPaymentMethod(): ?PaymentMethod
    {
        return $this->payment_method;
    }
}
