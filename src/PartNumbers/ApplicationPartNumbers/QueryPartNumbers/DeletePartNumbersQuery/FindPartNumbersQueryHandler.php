<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\DeletePartNumbersQuery;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\PartNumbersRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\DTOQuery\DTOPartNumbersQuery\PartNumbersQuery;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartNumbersFromManufacturers;

final class FindPartNumbersQueryHandler
{
    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private PartNumbersRepositoryInterface $artNumbersRepositoryInterface
    ) {}

    public function handler(PartNumbersQuery $partNumbersQuery): ?PartNumbersFromManufacturers
    {
        $id = $partNumbersQuery->getId();
        $this->inputErrorsPartNumbers->emptyData($id);

        $find_replacing_original_numbers = $this->artNumbersRepositoryInterface->findPartNumbersFromManufacturers($id);
        $this->inputErrorsPartNumbers->emptyEntity($find_replacing_original_numbers);

        return $find_replacing_original_numbers;
    }
}
