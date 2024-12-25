<?php

namespace App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse;

use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\PaymentMethod;


interface PaymentMethodRepositoryInterface
{
    public function findOneByPaymentMethod(string $payment_method): ?PaymentMethod;
}
