<?php

namespace App\PartNumbers\ApplicationPartNumbers\QuerySides\EditSidesQuery;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Sides;
use App\PartNumbers\ApplicationPartNumbers\QuerySides\DTOQuery\DTOSidesQuery\SidesQuery;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\SidesRepositoryInterface;

final class FindOneByIdSidesQueryHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private SidesRepositoryInterface $sidesRepositoryInterface
    ) {}

    public function handler(SidesQuery $sidesQuery): ?Sides
    {

        $id = $sidesQuery->getId();
        $this->inputErrorsPartNumbers->emptyData($id);
        $participant = $sidesQuery->getIdParticipant();
        $this->inputErrorsPartNumbers->userIsNotIdentified($participant);

        $edit_side = $this->sidesRepositoryInterface->findOneByIdSides($id, $participant);
        $this->inputErrorsPartNumbers->emptyEntity($edit_side);

        return $edit_side;
    }
}
