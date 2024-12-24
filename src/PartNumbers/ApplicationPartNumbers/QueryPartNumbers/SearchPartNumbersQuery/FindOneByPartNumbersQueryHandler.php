<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\SearchPartNumbersQuery;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\PartNumbersRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\DTOQuery\DTOPartNumbersQuery\PartNumbersQuery;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartNumbersFromManufacturers;

final class FindOneByPartNumbersQueryHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private PartNumbersRepositoryInterface $partNumbersRepositoryInterface
    ) {}

    public function handler(PartNumbersQuery $partNumbersQuery): ?PartNumbersFromManufacturers
    {

        $part_number = strtolower(preg_replace(
            '#\s#u',
            '',
            $partNumbersQuery->getPartNumber()
        ));
        $this->inputErrorsPartNumbers->emptyData($part_number);

        $findOneByPartNumbers = $this->partNumbersRepositoryInterface->findOneByPartNumber($part_number);

        return $findOneByPartNumbers;
    }
}
