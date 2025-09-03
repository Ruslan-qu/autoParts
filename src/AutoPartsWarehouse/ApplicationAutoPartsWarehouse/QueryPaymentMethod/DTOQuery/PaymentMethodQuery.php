<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryPaymentMethod\DTOQuery;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryPaymentMethod\DTOQuery\MapPaymentMethodQuery;


final class PaymentMethodQuery extends MapPaymentMethodQuery
{
    protected ?int $id = null;

    protected ?string $method = null;

    protected ?Participant $id_participant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function getIdParticipant(): ?Participant
    {
        return $this->id_participant;
    }
}
