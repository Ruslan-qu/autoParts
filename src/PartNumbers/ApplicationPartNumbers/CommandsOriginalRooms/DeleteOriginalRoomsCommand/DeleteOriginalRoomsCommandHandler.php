<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsOriginalRooms\DeleteOriginalRoomsCommand;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\OriginalRoomsRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsOriginalRooms\DTOCommands\DTOOriginalRoomsObjCommand\OriginalRoomsObjCommand;

final class DeleteOriginalRoomsCommandHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private OriginalRoomsRepositoryInterface $originalRoomsRepositoryInterface,
    ) {}

    public function handler(OriginalRoomsObjCommand $originalRoomsObjCommand): ?int
    {
        $original_rooms = $originalRoomsObjCommand->getOriginalRooms();
        $successfully_delete = $this->originalRoomsRepositoryInterface->delete($original_rooms);

        return $successfully_delete['delete'];
    }
}
