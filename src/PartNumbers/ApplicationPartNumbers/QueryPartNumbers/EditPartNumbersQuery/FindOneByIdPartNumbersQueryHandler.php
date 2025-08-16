<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\EditPartNumbersQuery;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\PartNumbersRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\DTOQuery\DTOPartNumbersQuery\PartNumbersQuery;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartNumbersFromManufacturers;


final class FindOneByIdPartNumbersQueryHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private PartNumbersRepositoryInterface $partNumbersRepositoryInterface
    ) {}

    public function handler(PartNumbersQuery $partNumbersQuery): ?array
    {

        $id = $partNumbersQuery->getId();
        $this->inputErrorsPartNumbers->emptyData($id);

        $participant = $partNumbersQuery->getIdParticipant();
        $this->inputErrorsPartNumbers->userIsNotIdentified($participant);

        $edit_part_numbers = $this->partNumbersRepositoryInterface->findOneByIdPartNumber($id, $participant);
        $this->inputErrorsPartNumbers->emptyEntity($edit_part_numbers);

        return $edit_part_numbers;
    }
}
