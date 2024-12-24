<?php

namespace App\Counterparty\InfrastructureCounterparty\ApiCounterparty\AdapterAutoPartsWarehouse;

use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\DTOQuery\DTOPartNameQuery\PartNameQuery;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\PartNumbersRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\DTOQuery\DTOPartNumbersQuery\PartNumbersQuery;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartNumbersFromManufacturers;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\EditPartNumbersQuery\FindIdPartNumbersQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\SearchPartNumbersQuery\FindOneByPartNameQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOPartNumbersCommand\PartNumbersCommand;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\SavePartNumbersCommand\SavePartNumbersCommandHandler;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\SearchPartNumbersQuery\FindOneByPartNumbersQueryHandler;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\AdapterAutoPartsWarehouse\AdapterAutoPartsWarehousePartNumbersInterface;
use App\Counterparty\InfrastructureCounterparty\ApiCounterparty\AdapterAutoPartsWarehouse\AdapterAutoPartsWarehouseCounterpartyInterface;

class AdapterAutoPartsWarehouseCounterparty implements AdapterAutoPartsWarehouseCounterpartyInterface
{

    public function __construct(
        private PartNumbersRepositoryInterface $partNumbersRepositoryInterface,
        private FindOneByPartNumbersQueryHandler $findOneByPartNumbersQueryHandler,
        private SavePartNumbersCommandHandler $savePartNumbersCommandHandler,
        private FindIdPartNumbersQueryHandler $findIdPartNumbersQueryHandler,
        private FindOneByPartNameQueryHandler $findOneByPartNameQueryHandler,
    ) {}

    public function counterpartySearch(array $arr_counterparty): ?array
    {

        foreach ($arr_counterparty as $key => $value) {

            $map_counterparty = ['name_counterparty' => $value['counterparty']];

            $part_name = $this->findOneByPartNameQueryHandler
                ->handler(new CounterpartyQuery($map_counterparty));

            if (empty($part_number)) {

                $arr_saving_information['id'] = $this->savePartNumbersCommandHandler
                    ->handler(new PartNumbersCommand($arr_map_part_name));

                $part_number = $this->findIdPartNumbersQueryHandler
                    ->handler(new PartNumbersQuery($arr_saving_information));
            }

            $arr_part_number[$key] = ['id_part_number' => $part_number];
        }

        return $arr_part_number;
    }
}
