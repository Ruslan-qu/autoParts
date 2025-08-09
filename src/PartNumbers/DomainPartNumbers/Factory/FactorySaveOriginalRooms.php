<?php

namespace App\PartNumbers\DomainPartNumbers\Factory;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\OriginalRoomsRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsOriginalRooms\SaveOriginalRoomsCommand\SaveObjectOriginalNumber;
use App\PartNumbers\ApplicationPartNumbers\CommandsOriginalRooms\DTOCommands\DTOOriginalRoomsCommand\OriginalRoomsCommand;
use App\PartNumbers\ApplicationPartNumbers\CommandsOriginalRooms\SaveOriginalRoomsCommand\SaveObjectReplacingOriginalNumber;

class FactorySaveOriginalRooms
{
    public function saveOriginalRooms(
        OriginalRoomsCommand $originalRoomsCommand,
        OriginalRoomsRepositoryInterface $originalRoomsRepositoryInterface
    ) {
        $input_errors = new InputErrorsPartNumbers;
        $input_errors->emptyData($originalRoomsCommand);

        if (
            !empty($originalRoomsCommand->getOriginalNumber())
            && $originalRoomsCommand->getReplacingOriginalNumber()[0] === null
        ) {
            $saveObjectOriginalNumber = new SaveObjectOriginalNumber;

            return $saveObjectOriginalNumber->objectOriginalNumber($originalRoomsCommand, $originalRoomsRepositoryInterface);
        } elseif (
            !empty($originalRoomsCommand->getOriginalNumber())
            && $originalRoomsCommand->getReplacingOriginalNumber()[0] != null
        ) {
            $saveObjectReplacingOriginalNumber = new SaveObjectReplacingOriginalNumber;

            return $saveObjectReplacingOriginalNumber->objectReplacingOriginalNumber($originalRoomsCommand, $originalRoomsRepositoryInterface);
        }
    }
}
