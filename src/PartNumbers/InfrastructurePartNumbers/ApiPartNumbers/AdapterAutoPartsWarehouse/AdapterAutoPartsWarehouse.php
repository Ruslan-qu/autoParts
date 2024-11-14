<?php

namespace App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\AdapterAutoPartsWarehouse;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\PartNumbersRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\DTOQuery\DTOPartNumbersQuery\PartNumbersQuery;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\EditPartNumbersQuery\FindIdPartNumbersQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\DTOQuery\DTOPartNumbersQuery\CreatePartNumbersQuery;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\SearchPartNumbersQuery\SearchPartNumbersQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOPartNumbersCommand\PartNumbersCommand;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\SavePartNumbersCommand\SavePartNumbersCommandHandler;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\AdapterAutoPartsWarehouse\AdapterAutoPartsWarehouseInterface;

class AdapterAutoPartsWarehouse implements AdapterAutoPartsWarehouseInterface
{

    public function __construct(
        private PartNumbersRepositoryInterface $partNumbersRepositoryInterface,
        private SearchPartNumbersQueryHandler $searchPartNumbersQueryHandler,
        private SavePartNumbersCommandHandler $savePartNumbersCommandHandler,
        private FindIdPartNumbersQueryHandler $findIdPartNumbersQueryHandler,
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
    ) {}


    public function searchIdDetails(array $arr_part_number): ?array
    {

        $this->inputErrorsPartNumbers->emptyData($arr_part_number['id_details']);

        $map_arr_part_numbers = ['part_number' => $arr_part_number['id_details']];

        $arr_part_numbers = $this->searchPartNumbersQueryHandler
            ->handler(new PartNumbersQuery($map_arr_part_numbers));

        if (empty($arr_part_numbers)) {

            $arr_saving_information['id'] = $this->savePartNumbersCommandHandler
                ->handler(new PartNumbersCommand($map_arr_part_numbers));


            $arr_part_numbers[] = $this->findIdPartNumbersQueryHandler
                ->handler(new PartNumbersQuery($arr_saving_information));
        }

        return $arr_part_numbers;
    }
}
