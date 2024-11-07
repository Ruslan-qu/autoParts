<?php

namespace App\Counterparty\ApplicationCounterparty\CommandsCounterparty\DeleteCounterpartyCommand;

use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\Counterparty\ApplicationCounterparty\CommandsCounterparty\DTOCommands\CounterpartyCommand;
use App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty\CounterpartyRepositoryInterface;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\AutoPartsWarehouseRepositoryInterface;

final class DeleteCounterpartyCommandHandler
{

    public function __construct(
        private CounterpartyRepositoryInterface $counterpartyRepositoryInterface,
        private AutoPartsWarehouseRepositoryInterface $autoPartsWarehouseRepositoryInterface
    ) {}

    public function handler(CounterpartyCommand $counterpartyCommand): void
    {

        $id = $counterpartyCommand->getId();
        if (empty($id)) {

            $arr_data_errors = ['Error' => 'Иди некорректное'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        $delete_counterparty = $this->counterpartyRepositoryInterface->findCounterparty($id);
        if (empty($delete_counterparty)) {

            $arr_data_errors = ['Error' => 'Иди не существует'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        $this->counterpartyRepositoryInterface->delete($delete_counterparty);
    }
}
