<?php

namespace App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\AdapterAutoPartsWarehouse;

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

class AdapterAutoPartsWarehousePartNumbers implements AdapterAutoPartsWarehousePartNumbersInterface
{

    public function __construct(
        private PartNumbersRepositoryInterface $partNumbersRepositoryInterface,
        private FindOneByPartNumbersQueryHandler $findOneByPartNumbersQueryHandler,
        private SavePartNumbersCommandHandler $savePartNumbersCommandHandler,
        private FindIdPartNumbersQueryHandler $findIdPartNumbersQueryHandler,
        private FindOneByPartNameQueryHandler $findOneByPartNameQueryHandler,
    ) {}


    public function searchIdDetails(array $arr_part_number): ?PartNumbersFromManufacturers
    {

        $map_arr_part_numbers = ['part_number' => $arr_part_number['id_details']];
        $part_number = $this->findOneByPartNumbersQueryHandler
            ->handler(new PartNumbersQuery($map_arr_part_numbers));

        if (empty($part_number)) {

            $arr_saving_information['id'] = $this->savePartNumbersCommandHandler
                ->handler(new PartNumbersCommand($map_arr_part_numbers));


            $part_number = $this->findIdPartNumbersQueryHandler
                ->handler(new PartNumbersQuery($arr_saving_information));
        }

        return $part_number;
    }

    public function partNumberSearch(array $arr_part_number): ?array
    {
        $arr_id_details = [];
        foreach ($arr_part_number as $key => $value) {

            $map_part_name = ['part_name' => $value['part_name']];

            $part_name = $this->findOneByPartNameQueryHandler
                ->handler(new PartNameQuery($map_part_name));
            $map_id_part_name = ['id_part_name' => $part_name];
            $arr_map_part_name = array_replace($value, $map_id_part_name);
            unset($arr_map_part_name['part_name']);

            $part_number = $this->findOneByPartNumbersQueryHandler
                ->handler(new PartNumbersQuery($arr_map_part_name));

            if (empty($part_number)) {

                $arr_saving_information['id'] = $this->savePartNumbersCommandHandler
                    ->handler(new PartNumbersCommand($arr_map_part_name));

                $part_number = $this->findIdPartNumbersQueryHandler
                    ->handler(new PartNumbersQuery($arr_saving_information));
            }

            $arr_id_details[$key] = ['id_details' => $part_number];
        }

        return $arr_id_details;
    }

    public function idPartNumbersSearch(array $arr_part_number): ?array
    {
        $arr_id_details = [];
        foreach ($arr_part_number as $key => $value) {

            $part_number = $this->findOneByPartNumbersQueryHandler
                ->handler(new PartNumbersQuery($value));

            if (empty($part_number)) {

                $arr_saving_information['id'] = $this->savePartNumbersCommandHandler
                    ->handler(new PartNumbersCommand($value));

                $part_number = $this->findIdPartNumbersQueryHandler
                    ->handler(new PartNumbersQuery($arr_saving_information));
            }

            $arr_id_details[$key] = ['id_details' => $part_number];
        }

        return $arr_id_details;
    }
}
