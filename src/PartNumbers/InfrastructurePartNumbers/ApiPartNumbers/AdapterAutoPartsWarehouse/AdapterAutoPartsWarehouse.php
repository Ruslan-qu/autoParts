<?php

namespace App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\AdapterAutoPartsWarehouse;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\PartNumbersRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\DTOQuery\DTOPartNumbersQuery\PartNumbersQuery;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartNumbersFromManufacturers;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\EditPartNumbersQuery\FindIdPartNumbersQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOPartNumbersCommand\PartNumbersCommand;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\SavePartNumbersCommand\SavePartNumbersCommandHandler;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\SearchPartNumbersQuery\FindOneByPartNumbersQueryHandler;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\AdapterAutoPartsWarehouse\AdapterAutoPartsWarehouseInterface;

class AdapterAutoPartsWarehouse implements AdapterAutoPartsWarehouseInterface
{

    public function __construct(
        private PartNumbersRepositoryInterface $partNumbersRepositoryInterface,
        private FindOneByPartNumbersQueryHandler $findOneByPartNumbersQueryHandler,
        private SavePartNumbersCommandHandler $savePartNumbersCommandHandler,
        private FindIdPartNumbersQueryHandler $findIdPartNumbersQueryHandler,
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
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
}
