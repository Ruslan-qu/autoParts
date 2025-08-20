<?php

namespace App\Counterparty\ApplicationCounterparty\CommandsCounterparty\DTOCommands\DTOCounterpartyObjCommand;

use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;
use App\Counterparty\ApplicationCounterparty\CommandsCounterparty\DTOCommands\DTOCounterpartyObjCommand\MapCounterpartyObjCommand;

final class CounterpartyObjCommand extends MapCounterpartyObjCommand
{
    protected ?Counterparty $counterparty = null;

    public function getCounterparty(): ?Counterparty
    {
        return $this->counterparty;
    }
}
