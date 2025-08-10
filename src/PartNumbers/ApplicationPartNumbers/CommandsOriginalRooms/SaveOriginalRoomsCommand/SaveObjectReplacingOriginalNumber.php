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

    /* public function objectReplacingOriginalNumber(
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
            $original_rooms->setReplacingOriginalNumber([
                $originalRoomsCommand->getOriginalNumber(),
                $originalRoomsCommand->getReplacingOriginalNumber()[0]
            ]);
            $original_rooms->setOriginalManufacturer($originalRoomsCommand->getOriginalManufacturer());
            $original_rooms->setIdParticipant($originalRoomsCommand->getIdParticipant());

            $id = $originalRoomsRepositoryInterface->save($original_rooms);
        } elseif ($find_one_by_original_rooms != null) {
            $this->countDuplicate(
                $originalRoomsCommand->getReplacingOriginalNumber()[0],
                $find_one_by_original_rooms->getReplacingOriginalNumber()[0],
                $originalRoomsRepositoryInterface
            );

            $find_one_by_original_rooms->setOriginalNumber($originalRoomsCommand->getOriginalNumber());
            $find_one_by_original_rooms->setReplacingOriginalNumber($originalRoomsCommand->getReplacingOriginalNumber());
            $find_one_by_original_rooms->setOriginalManufacturer($originalRoomsCommand->getOriginalManufacturer());
            $find_one_by_original_rooms->setIdParticipant($originalRoomsCommand->getIdParticipant());

            $id = $originalRoomsRepositoryInterface->edit($original_rooms);
        }

        return $id;
    }*/

    private function countDuplicate(
        string $edit_replacing_original_number,
        string $replacing_original_number,
        OriginalRoomsRepositoryInterface $originalRoomsRepositoryInterface
    ): static {
        if ($edit_replacing_original_number != $replacing_original_number) {

            $inputErrorsPartNumbers = new InputErrorsPartNumbers;
            /* Валидация дублей */
            $count_duplicate = $originalRoomsRepositoryInterface
                ->numberDoubles(['replacing_original_number' => $edit_replacing_original_number[0]]);
            $inputErrorsPartNumbers->errorDuplicate($count_duplicate);
        }

        return $this;
    }
}
