<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryPaymentMethod\SearchPaymentMethodQuery;

use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\PaymentMethodRepositoryInterface;


final class FindAllPaymentMethodQueryHandler
{

    public function __construct(
        private PaymentMethodRepositoryInterface $paymentMethodRepositoryInterface
    ) {}

    public function handler(): ?array
    {

        $paymentMethod = $this->paymentMethodRepositoryInterface->findAllPaymentMethod();

        return $paymentMethod;
    }
}
