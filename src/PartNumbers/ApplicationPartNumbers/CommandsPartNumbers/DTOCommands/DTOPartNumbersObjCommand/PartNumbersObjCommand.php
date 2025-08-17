<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOPartNumbersObjCommand;

use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartNumbersFromManufacturers;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOPartNumbersObjCommand\MapPartNumbersObjCommand;

final class PartNumbersObjCommand extends MapPartNumbersObjCommand
{
    protected ?PartNumbersFromManufacturers $part_numbers = null;

    public function getPartNumbers(): ?PartNumbersFromManufacturers
    {
        return $this->part_numbers;
    }
}
