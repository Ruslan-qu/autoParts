<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryOriginalRooms\DeleteOriginalRoomsQuery;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\OriginalRoomsRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryOriginalRooms\DTOQuery\DTOOriginalRoomsQuery\OriginalRoomsQuery;

final class FindOriginalRoomsQueryHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private OriginalRoomsRepositoryInterface $originalRoomsRepositoryInterface
    ) {}

    public function handler(OriginalRoomsQuery $originalRoomsQuery): ?array
    {
        $id = $originalRoomsQuery->getId();
        $this->inputErrorsPartNumbers->emptyData($id);

        $find_original_rooms['original_rooms'] = $this->originalRoomsRepositoryInterface->findOriginalRooms($id);
        $this->inputErrorsPartNumbers->emptyEntity($find_original_rooms);

        return $find_original_rooms;
    }
}
