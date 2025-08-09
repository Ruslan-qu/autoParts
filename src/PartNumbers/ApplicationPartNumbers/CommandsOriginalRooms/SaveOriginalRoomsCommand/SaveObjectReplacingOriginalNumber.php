<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsOriginalRooms\SaveOriginalRoomsCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Availability;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\OriginalRooms;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\AvailabilityRepositoryInterface;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\OriginalRoomsRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsAvailability\DTOCommands\DTOAvailabilityCommand\AvailabilityCommand;
use App\PartNumbers\ApplicationPartNumbers\CommandsOriginalRooms\DTOCommands\DTOOriginalRoomsCommand\OriginalRoomsCommand;

final class SaveObjectReplacingOriginalNumber
{

    public function objectReplacingOriginalNumber(
        OriginalRoomsCommand $originalRoomsCommand,
        OriginalRoomsRepositoryInterface $originalRoomsRepositoryInterface
    ): ?int {

        $original_rooms = new OriginalRooms;

        $find_one_by_original_rooms = $originalRoomsRepositoryInterface->findOneByOriginalRooms(
            $originalRoomsCommand->getOriginalNumber(),
            $originalRoomsCommand->getIdParticipant()
        );
        if ($find_one_by_original_rooms === null) {
            $original_rooms->setOriginalNumber($originalRoomsCommand->getOriginalNumber());
            $original_rooms->setReplacingOriginalNumber([$originalRoomsCommand->getOriginalNumber()]);
            $original_rooms->setOriginalManufacturer($originalRoomsCommand->getOriginalManufacturer());
            $original_rooms->setIdParticipant($originalRoomsCommand->getIdParticipant());

            $id = $originalRoomsRepositoryInterface->save($original_rooms);
        } elseif ($find_one_by_original_rooms != null) {

            $find_one_by_original_rooms->setOriginalNumber($originalRoomsCommand->getOriginalNumber());
            $find_one_by_original_rooms->setReplacingOriginalNumber([$originalRoomsCommand->getReplacingOriginalNumber()]);
            $find_one_by_original_rooms->setOriginalManufacturer($originalRoomsCommand->getOriginalManufacturer());
            $find_one_by_original_rooms->setIdParticipant($originalRoomsCommand->getIdParticipant());

            $id = $originalRoomsRepositoryInterface->edit($original_rooms);
        }

        return $id;
    }
}
