<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsAxles\DTOCommands\DTOAxlesCommand;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\PartNumbers\ApplicationPartNumbers\CommandsAxles\DTOCommands\DTOAxlesCommand\MapAxlesCommand;

final class AxlesCommand extends MapAxlesCommand
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
