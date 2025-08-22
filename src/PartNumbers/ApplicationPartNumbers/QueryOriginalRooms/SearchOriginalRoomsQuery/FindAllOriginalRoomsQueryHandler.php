<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryOriginalRooms\SearchOriginalRoomsQuery;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\OriginalRoomsRepositoryInterface;

final class FindAllOriginalRoomsQueryHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private OriginalRoomsRepositoryInterface $originalRoomsRepositoryInterface
    ) {}

    public function handler(): ?array
    {

        $findAllOriginalRooms = $this->originalRoomsRepositoryInterface->findAllOriginalRooms();

        return $findAllOriginalRooms;
    }
}
