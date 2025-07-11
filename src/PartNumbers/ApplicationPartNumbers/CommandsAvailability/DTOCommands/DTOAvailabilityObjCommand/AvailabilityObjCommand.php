<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsAvailability\DTOCommands\DTOAvailabilityObjCommand;

use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Availability;
use App\PartNumbers\ApplicationPartNumbers\CommandsAvailability\DTOCommands\DTOAvailabilityObjCommand\MapAvailabilityObjCommand;

final class AvailabilityObjCommand extends MapAvailabilityObjCommand
{
    protected ?Availability $availability = null;

    public function getAvailability(): ?Availability
    {
        return $this->availability;
    }
}
