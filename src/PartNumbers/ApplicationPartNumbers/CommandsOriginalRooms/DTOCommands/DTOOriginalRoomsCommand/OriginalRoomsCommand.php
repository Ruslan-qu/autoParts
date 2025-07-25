<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsOriginalRooms\DTOCommands\DTOOriginalRoomsCommand;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\PartNumbers\ApplicationPartNumbers\CommandsOriginalRooms\DTOCommands\DTOOriginalRoomsCommand\MapOriginalRoomsCommand;

final class OriginalRoomsCommand extends MapOriginalRoomsCommand
{
    protected ?int $id = null;

    protected ?string $original_number = null;

    protected ?array $replacing_original_number = null;

    protected ?string $original_manufacturer = null;

    protected ?Participant $id_participant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOriginalNumber(): ?string
    {
        return $this->original_number;
    }

    public function getOriginalManufacturer(): ?string
    {
        return $this->original_manufacturer;
    }

    public function getReplacingOriginalNumber(): ?array
    {
        return $this->replacing_original_number;
    }

    public function getIdParticipant(): ?Participant
    {
        return $this->id_participant;
    }
}
