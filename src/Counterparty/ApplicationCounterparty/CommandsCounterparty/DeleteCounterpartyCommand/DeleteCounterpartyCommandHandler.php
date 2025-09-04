<?php

namespace App\Counterparty\ApplicationCounterparty\CommandsCounterparty\DeleteCounterpartyCommand;

use App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty\CounterpartyRepositoryInterface;
use App\Counterparty\ApplicationCounterparty\CommandsCounterparty\DTOCommands\DTOCounterpartyObjCommand\CounterpartyObjCommand;

final class DeleteCounterpartyCommandHandler
{

    public function __construct(
        private CounterpartyRepositoryInterface $counterpartyRepositoryInterface,
    ) {}

    public function handler(CounterpartyObjCommand $counterpartyObjCommand): int
    {
        $counterparty = $counterpartyObjCommand->getCounterparty();

        return $this->counterpartyRepositoryInterface->delete($counterparty);
    }
}
