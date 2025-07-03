<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsSides\DTOCommands\DTOSidesCommand;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\PartNumbers\ApplicationPartNumbers\CommandsSides\DTOCommands\DTOSidesCommand\MapSidesCommand;

final class SidesCommand extends MapSidesCommand
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
