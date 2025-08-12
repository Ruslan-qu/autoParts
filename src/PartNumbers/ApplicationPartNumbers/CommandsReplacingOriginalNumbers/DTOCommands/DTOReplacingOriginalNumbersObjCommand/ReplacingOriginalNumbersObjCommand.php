<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsReplacingOriginalNumbers\DTOCommands\DTOReplacingOriginalNumbersObjCommand;

use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\ReplacingOriginalNumbers;
use App\PartNumbers\ApplicationPartNumbers\CommandsReplacingOriginalNumbers\DTOCommands\DTOReplacingOriginalNumbersObjCommand\MapReplacingOriginalNumbersObjCommand;

final class ReplacingOriginalNumbersObjCommand extends MapReplacingOriginalNumbersObjCommand
{
    protected ?ReplacingOriginalNumbers $replacing_original_numbers = null;

    public function getReplacingOriginalNumbers(): ?ReplacingOriginalNumbers
    {
        return $this->replacing_original_numbers;
    }
}
