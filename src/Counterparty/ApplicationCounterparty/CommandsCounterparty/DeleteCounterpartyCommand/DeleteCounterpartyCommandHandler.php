<?php

namespace App\Counterparty\ApplicationCounterparty\CommandsCounterparty\DeleteCounterpartyCommand;

use App\Counterparty\ApplicationCounterparty\Errors\InputErrors;
use App\Counterparty\ApplicationCounterparty\CommandsCounterparty\DTOCommands\CounterpartyCommand;
use App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty\CounterpartyRepositoryInterface;
use App\Counterparty\ApplicationCounterparty\CommandsCounterparty\DTOCommands\DTOCounterpartyObjCommand\CounterpartyObjCommand;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\AutoPartsWarehouseRepositoryInterface;

final class DeleteCounterpartyCommandHandler
{

    public function __construct(
        private InputErrors $inputErrors,
        private CounterpartyRepositoryInterface $counterpartyRepositoryInterface,
        private AutoPartsWarehouseRepositoryInterface $autoPartsWarehouseRepositoryInterface
    ) {}

    public function handler(CounterpartyObjCommand $counterpartyObjCommand): int
    {
        $counterparty = $counterpartyObjCommand->getCounterparty();

        return $this->counterpartyRepositoryInterface->delete($counterparty);
    }
}
