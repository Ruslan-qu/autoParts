<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsPartNames\DTOCommands\DTOPartNameObjCommand;

use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartName;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNames\DTOCommands\DTOPartNameObjCommand\MapPartNameObjCommand;

final class PartNameObjCommand extends MapPartNameObjCommand
{
    protected ?PartName $part_name = null;

    public function getPartName(): ?PartName
    {
        return $this->part_name;
    }
}
