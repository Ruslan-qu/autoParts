<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryAvailability\DTOQuery\DTOAvailabilityQuery;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\PartNumbers\ApplicationPartNumbers\QueryAvailability\DTOQuery\DTOAvailabilityQuery\MapAvailabilityQuery;

final class AvailabilityQuery extends MapAvailabilityQuery
{
    protected ?int $id = null;

    protected ?string $in_stock = null;

    protected ?Participant $id_participant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInStock(): ?string
    {
        return $this->in_stock;
    }

    public function getIdParticipant(): ?Participant
    {
        return $this->id_participant;
    }
}
