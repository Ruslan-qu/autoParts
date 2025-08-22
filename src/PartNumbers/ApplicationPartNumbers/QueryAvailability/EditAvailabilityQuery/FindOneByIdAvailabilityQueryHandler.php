<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryAvailability\EditAvailabilityQuery;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Availability;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\AvailabilityRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryAvailability\DTOQuery\DTOAvailabilityQuery\AvailabilityQuery;

final class FindOneByIdAvailabilityQueryHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private AvailabilityRepositoryInterface $availabilityRepositoryInterface
    ) {}

    public function handler(AvailabilityQuery $availabilityQuery): ?Availability
    {
        $id = $availabilityQuery->getId();
        $this->inputErrorsPartNumbers->emptyData($id);

        $participant = $availabilityQuery->getIdParticipant();
        $this->inputErrorsPartNumbers->userIsNotIdentified($participant);

        $edit_in_stock = $this->availabilityRepositoryInterface->findOneByIdAvailability($id, $participant);
        $this->inputErrorsPartNumbers->emptyEntity($edit_in_stock);

        return $edit_in_stock;
    }
}
