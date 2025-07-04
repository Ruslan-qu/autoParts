<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryBodies\DTOQuery\DTOBodiesQuery;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\PartNumbers\ApplicationPartNumbers\QueryBodies\DTOQuery\DTOBodiesQuery\MapBodiesQuery;

final class BodiesQuery extends MapBodiesQuery
{
    protected ?int $id = null;

    protected ?string $body = null;

    protected ?Participant $id_participant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function getIdParticipant(): ?Participant
    {
        return $this->id_participant;
    }
}
