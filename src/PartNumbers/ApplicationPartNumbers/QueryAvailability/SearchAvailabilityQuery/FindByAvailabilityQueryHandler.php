<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryAvailability\SearchAvailabilityQuery;

use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\AvailabilityRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryAvailability\DTOQuery\DTOAvailabilityQuery\AvailabilityQuery;

final class FindByAvailabilityQueryHandler
{

    public function __construct(
        private AvailabilityRepositoryInterface $availabilityRepositoryInterface
    ) {}

    public function handler(AvailabilityQuery $availabilityQuery): ?array
    {
        $id_participant = $availabilityQuery->getIdParticipant();
        $findAllAvailability = $this->availabilityRepositoryInterface
            ->findByAvailability($id_participant);

        return $findAllAvailability;
    }
}
