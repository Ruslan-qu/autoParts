<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryPartNames\EditPartNameQuery;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartName;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\PartNameRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNames\DTOQuery\DTOPartNameQuery\PartNameQuery;


final class FindOneByIdPartNameQueryHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private PartNameRepositoryInterface $partNameRepositoryInterface
    ) {}

    public function handler(PartNameQuery $partNameQuery): ?PartName
    {

        $id = $partNameQuery->getId();
        $this->inputErrorsPartNumbers->emptyData($id);
        $participant = $partNameQuery->getIdParticipant();
        $this->inputErrorsPartNumbers->userIsNotIdentified($participant);

        $edit_part_name = $this->partNameRepositoryInterface->findOneByIdPartName($id, $participant);
        $this->inputErrorsPartNumbers->emptyEntity($edit_part_name);

        return $edit_part_name;
    }
}
