<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsBodies\DTOCommands\DTOBodiesObjCommand;

use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Bodies;
use App\PartNumbers\ApplicationPartNumbers\CommandsBodies\DTOCommands\DTOBodiesObjCommand\MapBodiesObjCommand;

final class BodiesObjCommand extends MapBodiesObjCommand
{
    protected ?Bodies $bodies = null;

    public function getBodies(): ?Bodies
    {
        return $this->bodies;
    }
}
