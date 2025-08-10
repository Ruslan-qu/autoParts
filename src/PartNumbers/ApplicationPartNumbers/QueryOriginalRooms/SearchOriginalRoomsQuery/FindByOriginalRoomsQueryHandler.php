<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryOriginalRooms\SearchOriginalRoomsQuery;

use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\AvailabilityRepositoryInterface;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\OriginalRoomsRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryAvailability\DTOQuery\DTOAvailabilityQuery\AvailabilityQuery;

final class FindByOriginalRoomsQueryHandler
{

    public function __construct(
        private OriginalRoomsRepositoryInterface $originalRoomsRepositoryInterface
    ) {}

    public function handler(): ?array
    {

        $findAllAvailability = $this->originalRoomsRepositoryInterface
            ->findAllRoomsRepository();

        return $findAllAvailability;
    }
}
