<?php

namespace App\PartNumbers\ApplicationPartNumbers\QuerySides\DTOQuery\DTOSidesQuery;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\PartNumbers\ApplicationPartNumbers\QuerySides\DTOQuery\DTOSidesQuery\MapSidesQuery;

final class SidesQuery extends MapSidesQuery
{
    protected ?int $id = null;

    protected ?string $side = null;

    protected ?Participant $id_participant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSide(): ?string
    {
        return $this->side;
    }

    public function getIdParticipant(): ?Participant
    {
        return $this->id_participant;
    }
}
