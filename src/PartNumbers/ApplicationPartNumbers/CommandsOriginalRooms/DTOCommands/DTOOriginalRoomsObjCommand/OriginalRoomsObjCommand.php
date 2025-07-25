<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsOriginalRooms\DTOCommands\DTOOriginalRoomsObjCommand;

use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Availability;
use App\PartNumbers\ApplicationPartNumbers\CommandsAvailability\DTOCommands\DTOAvailabilityObjCommand\MapAvailabilityObjCommand;
use App\PartNumbers\ApplicationPartNumbers\CommandsOriginalRooms\DTOCommands\DTOOriginalRoomsObjCommand\MapOriginalRoomsObjCommand;

final class OriginalRoomsObjCommand extends MapOriginalRoomsObjCommand
{
    protected ?Availability $availability = null;

    public function getAvailability(): ?Availability
    {
        return $this->availability;
    }
}
