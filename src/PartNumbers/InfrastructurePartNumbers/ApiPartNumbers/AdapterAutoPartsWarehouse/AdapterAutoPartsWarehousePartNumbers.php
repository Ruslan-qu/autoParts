<?php

namespace App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\AdapterAutoPartsWarehouse;

use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\PartNumbersRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\DTOQuery\DTOPartNumbersQuery\PartNumbersQuery;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartNumbersFromManufacturers;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\DeletePartNumbersQuery\FindPartNumbersQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOPartNumbersCommand\PartNumbersCommand;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\SavePartNumbersCommand\SavePartNumbersCommandHandler;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\SearchPartNumbersQuery\FindOneByPartNumbersQueryHandler;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\AdapterAutoPartsWarehouse\AdapterAutoPartsWarehousePartNumbersInterface;

class AdapterAutoPartsWarehousePartNumbers implements AdapterAutoPartsWarehousePartNumbersInterface
{

    public function __construct(
        private PartNumbersRepositoryInterface $partNumbersRepositoryInterface,
        private FindOneByPartNumbersQueryHandler $findOneByPartNumbersQueryHandler,
        private SavePartNumbersCommandHandler $savePartNumbersCommandHandler,
        private FindPartNumbersQueryHandler $findPartNumbersQueryHandler
    ) {}


    public function searchIdDetails(array $arr_part_number): ?PartNumbersFromManufacturers
    {

        $part_number = $this->findOneByPartNumbersQueryHandler->handler(new PartNumbersQuery($arr_part_number));

        if (empty($part_number)) {

            $id['id'] = $this->savePartNumbersCommandHandler->handler(new PartNumbersCommand($arr_part_number));
            $part_number = $this->findPartNumbersQueryHandler->handler(new PartNumbersQuery($id));
        }

        return $part_number;
    }

    public function partNumberSearch(array $arr_part_number): ?array
    {
        $arr_processing_part_number = [];
        foreach ($arr_part_number as $key => $value) {

            $part_number = $this->findOneByPartNumbersQueryHandler->handler(new PartNumbersQuery($value));

            if (empty($part_number)) {

                $id['id'] = $this->savePartNumbersCommandHandler->handler(new PartNumbersCommand($value));
                $part_number = $this->findPartNumbersQueryHandler->handler(new PartNumbersQuery($id));
            }

            $arr_processing_part_number[$key] = ['part_number' => $part_number];
        }

        return $arr_processing_part_number;
    }
}
