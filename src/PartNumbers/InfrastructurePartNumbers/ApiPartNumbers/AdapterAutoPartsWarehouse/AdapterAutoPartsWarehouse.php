<?php

namespace App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\AdapterAutoPartsWarehouse;

use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\PartNumbersRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\DTOQuery\DTOPartNumbersQuery\CreatePartNumbersQuery;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\EditPartNumbersQuery\CreateFindIdPartNumbersQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\SearchPartNumbersQuery\CreateSearchPartNumbersQueryHandler;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOPartNumbersCommand\CreatePartNumbersCommand;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\SavePartNumbersCommand\CreateSavePartNumbersCommandHandler;
use App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\AdapterAutoPartsWarehouse\AdapterAutoPartsWarehouseInterface;

class AdapterAutoPartsWarehouse implements AdapterAutoPartsWarehouseInterface
{

    public function __construct(
        private PartNumbersRepositoryInterface $partNumbersRepositoryInterface,
        private CreateSearchPartNumbersQueryHandler $createSearchPartNumbersQueryHandler,
        private CreateSavePartNumbersCommandHandler $createSavePartNumbersCommandHandler,
        private CreateFindIdPartNumbersQueryHandler $createFindIdPartNumbersQueryHandler
    ) {}


    public function searchPartNumbersManufacturer(array $arr_part_number_manufactur): ?array
    {

        if (empty($arr_part_number_manufactur['id_details'])) {
            return Null;
        }
        $map_arr_part_numbers_manufacturer = ['part_number' => $arr_part_number_manufactur['id_details']];

        $object_original_number = $this->createSearchPartNumbersQueryHandler
            ->handler(new CreatePartNumbersQuery($map_arr_part_numbers_manufacturer));

        if (empty($object_original_number)) {
            $map_arr_part_numbers_manufacturer['manufacturer'] = $arr_part_number_manufactur['id_manufacturer'];

            $arr_saving_information['id'] = $this->createSavePartNumbersCommandHandler
                ->handler(new CreatePartNumbersCommand($map_arr_part_numbers_manufacturer));


            $object_original_number = $this->createFindIdPartNumbersQueryHandler
                ->handler(new CreatePartNumbersQuery($arr_saving_information));
            dd($object_original_number);
        }



        return new CreatePartNumbersQuery($arr_saving_information);
    }
}
