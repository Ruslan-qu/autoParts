<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsBodies\DTOCommands\DTOBodiesCommand;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\PartNumbers\ApplicationPartNumbers\CommandsBodies\DTOCommands\DTOBodiesCommand\MapBodiesCommand;

final class BodiesCommand extends MapBodiesCommand
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
