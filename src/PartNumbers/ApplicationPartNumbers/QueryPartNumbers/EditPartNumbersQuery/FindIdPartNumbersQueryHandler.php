<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\EditPartNumbersQuery;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\DTOQuery\DTOPartNumbersQuery\PartNumbersQuery;
use App\PartNumbers\InfrastructurePartNumbers\RepositoryPartNumbers\PartNumbersFromManufacturersRepository;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartNumbersFromManufacturers;


final class FindIdPartNumbersQueryHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private PartNumbersFromManufacturersRepository $partNumbersFromManufacturersRepository
    ) {}

    public function handler(PartNumbersQuery $partNumbersQuery): ?PartNumbersFromManufacturers
    {

        $id = $partNumbersQuery->getId();
        $this->inputErrorsPartNumbers->emptyData($id);

        $edit_part_numbers = $this->partNumbersFromManufacturersRepository->findPartNumbersFromManufacturers($id);
        $this->inputErrorsPartNumbers->emptyEntity($edit_part_numbers);

        return $edit_part_numbers;
    }
}
