<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsAvailability\DTOCommands\DTOAvailabilityCommand;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\PartNumbers\ApplicationPartNumbers\CommandsAvailability\DTOCommands\DTOAvailabilityCommand\MapAvailabilityCommand;

final class AvailabilityCommand extends MapAvailabilityCommand
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
