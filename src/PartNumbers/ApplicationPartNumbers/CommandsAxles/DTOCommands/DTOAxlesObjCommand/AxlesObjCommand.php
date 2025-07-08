<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsAxles\DTOCommands\DTOAxlesObjCommand;

use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Axles;
use App\PartNumbers\ApplicationPartNumbers\CommandsAxles\DTOCommands\DTOAxlesObjCommand\MapAxlesObjCommand;

final class AxlesObjCommand extends MapAxlesObjCommand
{
    protected ?Axles $axles = null;

    public function getAxles(): ?Axles
    {
        return $this->axles;
    }
}
