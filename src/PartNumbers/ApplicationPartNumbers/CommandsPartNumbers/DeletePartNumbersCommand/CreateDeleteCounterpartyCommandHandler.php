<?php

namespace App\Counterparty\ApplicationCounterparty\CommandsCounterparty\DeleteCounterpartyCommand;

use App\Counterparty\ApplicationCounterparty\CommandsCounterparty\DTOCommands\CreateCounterpartyCommand;
use App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty\CounterpartyRepositoryInterface;

final class CreateDeleteCounterpartyCommandHandler
{
    private $counterparty_repository_interface;

    public function __construct(
        CounterpartyRepositoryInterface $counterparty_repository_interface
    ) {
        $this->counterparty_repository_interface = $counterparty_repository_interface;
    }

    public function handler(CreateCounterpartyCommand $createCounterpartyCommand): array
    {

        $id = $createCounterpartyCommand->getId();
        if (empty($id)) {

            $arr_errors_id['errors'] = [
                'doubles' => 'Поставщик не существует'
            ];
            return $arr_errors_id;
        }

        $delete_counterparty = $this->counterparty_repository_interface->findCounterparty($id);
        if (empty($delete_counterparty)) {

            $arr_errors_id['errors'] = [
                'doubles' => 'Поставщик не найден'
            ];
            return $arr_errors_id;
        }

        $successfully_delete = $this->counterparty_repository_interface->delete($delete_counterparty);

        $successfully['successfully'] = $successfully_delete;
        return $successfully;
    }
}
