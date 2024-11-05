<?php

namespace App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\AdapterAutoPartsWarehouse;

use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\PartNumbersRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\DTOQuery\DTOPartNumbersQuery\CreatePartNumbersQuery;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOPartNumbersCommand\PartNumbersCommand;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\SavePartNumbersCommand\SavePartNumbersCommandHandler;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\EditPartNumbersQuery\CreateFindIdPartNumbersQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\SearchPartNumbersQuery\CreateSearchPartNumbersQueryHandler;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\AdapterAutoPartsWarehouse\AdapterAutoPartsWarehouseInterface;

class AdapterAutoPartsWarehouse implements AdapterAutoPartsWarehouseInterface
{

    public function __construct(
        private PartNumbersRepositoryInterface $partNumbersRepositoryInterface,
        private CreateSearchPartNumbersQueryHandler $createSearchPartNumbersQueryHandler,
        private SavePartNumbersCommandHandler $savePartNumbersCommandHandler,
        private CreateFindIdPartNumbersQueryHandler $createFindIdPartNumbersQueryHandler
    ) {}


    public function searchIdDetails(array $arr_part_number): ?array
    {

        if (empty($arr_part_number['id_details'])) {
            return Null;
        }
        $map_arr_part_numbers = ['part_number' => $arr_part_number['id_details']];

        $arr_part_numbers = $this->createSearchPartNumbersQueryHandler
            ->handler(new CreatePartNumbersQuery($map_arr_part_numbers));

        if (empty($arr_part_numbers)) {

            $arr_saving_information['id'] = $this->savePartNumbersCommandHandler
                ->handler(new PartNumbersCommand($map_arr_part_numbers));


            $arr_part_numbers[] = $this->createFindIdPartNumbersQueryHandler
                ->handler(new CreatePartNumbersQuery($arr_saving_information));
        }



        return $arr_part_numbers;
    }
}
