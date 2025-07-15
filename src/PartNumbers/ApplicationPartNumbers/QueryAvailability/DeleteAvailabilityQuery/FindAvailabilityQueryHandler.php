<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryAvailability\DeleteAvailabilityQuery;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\AvailabilityRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryAvailability\DTOQuery\DTOAvailabilityQuery\AvailabilityQuery;

final class FindAvailabilityQueryHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private AvailabilityRepositoryInterface $availabilityRepositoryInterface
    ) {}

    public function handler(AvailabilityQuery $availabilityQuery): ?array
    {
        $id = $availabilityQuery->getId();
        $this->inputErrorsPartNumbers->emptyData($id);

        $find_availability['availability'] = $this->availabilityRepositoryInterface->findAvailability($id);
        $this->inputErrorsPartNumbers->emptyEntity($find_availability);

        return $find_availability;
    }
}
