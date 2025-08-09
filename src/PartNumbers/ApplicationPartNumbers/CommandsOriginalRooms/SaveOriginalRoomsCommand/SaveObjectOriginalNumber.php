<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsOriginalRooms\SaveOriginalRoomsCommand;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\OriginalRooms;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\OriginalRoomsRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsOriginalRooms\DTOCommands\DTOOriginalRoomsCommand\OriginalRoomsCommand;

class SaveObjectOriginalNumber
{


    public function objectOriginalNumber(
        OriginalRoomsCommand $originalRoomsCommand,
        OriginalRoomsRepositoryInterface $originalRoomsRepositoryInterface
    ): ?int {

        $inputErrorsPartNumbers = new InputErrorsPartNumbers;

        /* Валидация дублей */
        $count_duplicate = $originalRoomsRepositoryInterface
            ->numberDoubles(['original_number' => $originalRoomsCommand->getOriginalNumber()]);
        $inputErrorsPartNumbers->errorDuplicate($count_duplicate);

        $original_rooms = new OriginalRooms;
        $original_rooms->setOriginalNumber($originalRoomsCommand->getOriginalNumber());
        $original_rooms->setReplacingOriginalNumber([$originalRoomsCommand->getOriginalNumber()]);
        $original_rooms->setOriginalManufacturer($originalRoomsCommand->getOriginalManufacturer());
        $original_rooms->setIdParticipant($originalRoomsCommand->getIdParticipant());

        $id = $originalRoomsRepositoryInterface->save($original_rooms);

        return $id;
    }
}
