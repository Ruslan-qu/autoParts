<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsOriginalRooms\DTOCommands\DTOOriginalRoomsObjCommand;

use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\OriginalRooms;
use App\PartNumbers\ApplicationPartNumbers\CommandsOriginalRooms\DTOCommands\DTOOriginalRoomsObjCommand\MapOriginalRoomsObjCommand;

final class OriginalRoomsObjCommand extends MapOriginalRoomsObjCommand
{
    protected ?OriginalRooms $original_rooms = null;

    public function getOriginalRooms(): ?OriginalRooms
    {
        return $this->original_rooms;
    }
}
