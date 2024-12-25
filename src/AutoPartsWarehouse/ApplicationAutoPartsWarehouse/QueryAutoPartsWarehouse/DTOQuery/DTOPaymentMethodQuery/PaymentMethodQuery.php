<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOPaymentMethodQuery;

use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOPaymentMethodQuery\MapPaymentMethodQuery;

final class PaymentMethodQuery extends MapPaymentMethodQuery
{
    protected ?int $id = null;

    protected ?string $method = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }
}
