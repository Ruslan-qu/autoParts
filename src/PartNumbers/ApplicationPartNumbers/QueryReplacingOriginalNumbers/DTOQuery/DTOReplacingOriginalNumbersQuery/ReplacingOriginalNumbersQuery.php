<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryReplacingOriginalNumbers\DTOQuery\DTOReplacingOriginalNumbersQuery;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\OriginalRooms;
use App\PartNumbers\ApplicationPartNumbers\QueryReplacingOriginalNumbers\DTOQuery\DTOReplacingOriginalNumbersQuery\MapReplacingOriginalNumbersQuery;

final class ReplacingOriginalNumbersQuery extends MapReplacingOriginalNumbersQuery
{
    protected ?int $id = null;

    protected ?string $replacing_original_number = null;

    protected ?OriginalRooms $id_original_number = null;

    protected ?Participant $id_participant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReplacingOriginalNumber(): ?string
    {
        return $this->replacing_original_number;
    }

    public function getIdOriginalNumber(): ?OriginalRooms
    {
        return $this->id_original_number;
    }

    public function getIdParticipant(): ?Participant
    {
        return $this->id_participant;
    }
}
