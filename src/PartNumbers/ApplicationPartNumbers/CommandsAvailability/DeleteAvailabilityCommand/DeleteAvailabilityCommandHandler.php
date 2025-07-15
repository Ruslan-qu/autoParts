<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsAvailability\DeleteAvailabilityCommand;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\AvailabilityRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsAvailability\DTOCommands\DTOAvailabilityObjCommand\AvailabilityObjCommand;

final class DeleteAvailabilityCommandHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private AvailabilityRepositoryInterface $availabilityRepositoryInterface,
    ) {}

    public function handler(AvailabilityObjCommand $availabilityObjCommand): ?int
    {
        $availability = $availabilityObjCommand->getAvailability();
        $successfully_delete = $this->availabilityRepositoryInterface->delete($availability);

        return $successfully_delete['delete'];
    }
}
