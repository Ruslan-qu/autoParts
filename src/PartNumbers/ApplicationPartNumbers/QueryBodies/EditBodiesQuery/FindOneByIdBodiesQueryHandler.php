<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryBodies\EditBodiesQuery;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Bodies;
use App\PartNumbers\ApplicationPartNumbers\QueryBodies\DTOQuery\DTOBodiesQuery\BodiesQuery;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\BodiesRepositoryInterface;

final class FindOneByIdBodiesQueryHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private BodiesRepositoryInterface $bodiesRepositoryInterface
    ) {}

    public function handler(BodiesQuery $bodiesQuery): ?Bodies
    {

        $id = $bodiesQuery->getId();
        $this->inputErrorsPartNumbers->emptyData($id);
        $participant = $bodiesQuery->getIdParticipant();
        $this->inputErrorsPartNumbers->userIsNotIdentified($participant);

        $edit_body = $this->bodiesRepositoryInterface->findOneByIdBodies($id, $participant);
        $this->inputErrorsPartNumbers->emptyEntity($edit_body);

        return $edit_body;
    }
}
