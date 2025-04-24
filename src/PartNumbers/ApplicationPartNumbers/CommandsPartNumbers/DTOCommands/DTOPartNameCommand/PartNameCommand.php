<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOPartNameCommand;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOPartNameCommand\MapPartNameCommand;

final class PartNameCommand extends MapPartNameCommand
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
