<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryPartNames\DeletePartNameQuery;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartName;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\PartNameRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNames\DTOQuery\DTOPartNameQuery\PartNameQuery;

final class FindPartNameQueryHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private PartNameRepositoryInterface $partNameRepositoryInterface
    ) {}

    public function handler(PartNameQuery $partNameQuery): ?array
    {
        $id = $partNameQuery->getId();
        $this->inputErrorsPartNumbers->emptyData($id);

        $find_part_name['part_name'] = $this->partNameRepositoryInterface->findPartName($id);
        $this->inputErrorsPartNumbers->emptyEntity($find_part_name);

        return $find_part_name;
    }
}
