<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryOriginalRooms\EditOriginalRoomsQuery;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\OriginalRooms;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\OriginalRoomsRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryOriginalRooms\DTOQuery\DTOOriginalRoomsQuery\OriginalRoomsQuery;

final class FindOneByIdOriginalRoomsQueryHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private OriginalRoomsRepositoryInterface $originalRoomsRepositoryInterface
    ) {}

    public function handler(OriginalRoomsQuery $originalRoomsQuery): ?OriginalRooms
    {
        $id = $originalRoomsQuery->getId();
        $this->inputErrorsPartNumbers->emptyData($id);
        $participant = $originalRoomsQuery->getIdParticipant();
        $this->inputErrorsPartNumbers->userIsNotIdentified($participant);

        $edit_original_number = $this->originalRoomsRepositoryInterface->findOneByIdOriginalRooms($id, $participant);
        $this->inputErrorsPartNumbers->emptyEntity($edit_original_number);

        return $edit_original_number;
    }
}
