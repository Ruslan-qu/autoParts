<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsSides\DTOCommands\DTOSidesObjCommand;

use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Sides;
use App\PartNumbers\ApplicationPartNumbers\CommandsSides\DTOCommands\DTOSidesObjCommand\MapSidesObjCommand;

final class SidesObjCommand extends MapSidesObjCommand
{
    protected ?Sides $sides = null;

    public function getSides(): ?Sides
    {
        return $this->sides;
    }
}
