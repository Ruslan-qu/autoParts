<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\SearchPartNumbersQuery;

use App\Counterparty\ApplicationCounterparty\QueryCounterparty\DTOQuery\CreateCounterpartyQuery;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\PartNumbersRepositoryInterface;
use App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty\CounterpartyRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\DTOQuery\DTOPartNumbersQuery\CreatePartNumbersQuery;


final class CreateSearchPartNumbersQueryHandler
{
    private $part_numbers_repository_interface;

    public function __construct(
        PartNumbersRepositoryInterface $partNumbersRepositoryInterface
    ) {
        $this->part_numbers_repository_interface = $partNumbersRepositoryInterface;
    }

    public function handler(CreatePartNumbersQuery $createPartNumbersQuery): ?array
    {

        $part_number = strtolower(preg_replace(
            '#\s#',
            '',
            $createPartNumbersQuery->getPartNumber()
        ));

        $manufacturer = strtolower(preg_replace(
            '#\s#',
            '',
            $createPartNumbersQuery->getManufacturer()
        ));

        $id_part_name = $createPartNumbersQuery->getIdPartName();

        $id_car_brand = $createPartNumbersQuery->getIdCarBrand();

        $id_side = $createPartNumbersQuery->getIdSide();

        $id_body = $createPartNumbersQuery->getIdBody();

        $id_axle = $createPartNumbersQuery->getIdAxle();

        $id_in_stock = $createPartNumbersQuery->getIdInStock();

        $id_original_number = $createPartNumbersQuery->getIdOriginalNumber();

        $counterparty = $this->part_numbers_repository_interface->findByCounterparty($name_counterparty);

        return $counterparty;
    }
}
