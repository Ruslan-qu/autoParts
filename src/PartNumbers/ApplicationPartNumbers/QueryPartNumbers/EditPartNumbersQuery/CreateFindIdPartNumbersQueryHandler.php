<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\EditPartNumbersQuery;

use App\PartNumbers\InfrastructurePartNumbers\RepositoryPartNumbers\PartNumbersFromManufacturersRepository;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartNumbersFromManufacturers;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\DTOQuery\DTOPartNumbersQuery\CreatePartNumbersQuery;


final class CreateFindIdPartNumbersQueryHandler
{
    private $part_numbers_from_manufacturers_repository;

    public function __construct(
        PartNumbersFromManufacturersRepository $partNumbersFromManufacturersRepository
    ) {
        $this->part_numbers_from_manufacturers_repository = $partNumbersFromManufacturersRepository;
    }

    public function handler(CreatePartNumbersQuery $createPartNumbersQuery): ?PartNumbersFromManufacturers
    {
        //dd($createCounterpartyQuery->getId());
        $id = $createPartNumbersQuery->getId();

        if (empty($id)) {
            return NULL;
        }

        $edit_part_numbers = $this->part_numbers_from_manufacturers_repository->findPartNumbersFromManufacturers($id);

        return $edit_part_numbers;
    }
}
