<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryAxles\DTOQuery\DTOAxlesQuery;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\PartNumbers\ApplicationPartNumbers\QueryAxles\DTOQuery\DTOAxlesQuery\MapAxlesQuery;

final class AxlesQuery extends MapAxlesQuery
{
    protected ?int $id = null;

    protected ?string $axle = null;

    protected ?Participant $id_participant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAxle(): ?string
    {
        return $this->axle;
    }

    public function getIdParticipant(): ?Participant
    {
        return $this->id_participant;
    }
}
