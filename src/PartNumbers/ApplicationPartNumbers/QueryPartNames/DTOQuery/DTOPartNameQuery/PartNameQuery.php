<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryPartNames\DTOQuery\DTOPartNameQuery;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNames\DTOQuery\DTOPartNameQuery\MapPartNameQuery;

final class PartNameQuery extends MapPartNameQuery
{
    protected ?int $id = null;

    protected ?string $part_name = null;

    protected ?Participant $id_participant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPartName(): ?string
    {
        return $this->part_name;
    }

    public function getIdParticipant(): ?Participant
    {
        return $this->id_participant;
    }
}
