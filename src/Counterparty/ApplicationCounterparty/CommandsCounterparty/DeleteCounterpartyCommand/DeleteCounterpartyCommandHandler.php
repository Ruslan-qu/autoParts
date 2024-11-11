<?php

namespace App\Counterparty\ApplicationCounterparty\CommandsCounterparty\DeleteCounterpartyCommand;

use App\Counterparty\ApplicationCounterparty\Errors\InputErrors;
use App\Counterparty\ApplicationCounterparty\CommandsCounterparty\DTOCommands\CounterpartyCommand;
use App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty\CounterpartyRepositoryInterface;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\AutoPartsWarehouseRepositoryInterface;

final class DeleteCounterpartyCommandHandler
{

    public function __construct(
        private InputErrors $inputErrors,
        private CounterpartyRepositoryInterface $counterpartyRepositoryInterface,
        private AutoPartsWarehouseRepositoryInterface $autoPartsWarehouseRepositoryInterface
    ) {}

    public function handler(CounterpartyCommand $counterpartyCommand): int
    {

        $id = $counterpartyCommand->getId();
        $this->inputErrors->emptyData($id);

        $еntity = $this->counterpartyRepositoryInterface->findCounterparty($id);
        $this->inputErrors->emptyEntity($еntity);

        $successfully_save = $this->counterpartyRepositoryInterface->delete($еntity);
        $id = $successfully_save['delete'];

        return $id;
    }
}
