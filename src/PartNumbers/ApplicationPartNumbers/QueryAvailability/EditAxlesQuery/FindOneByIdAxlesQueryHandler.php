<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryAxles\EditAxlesQuery;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Axles;
use App\PartNumbers\ApplicationPartNumbers\QueryAxles\DTOQuery\DTOAxlesQuery\AxlesQuery;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\AxlesRepositoryInterface;

final class FindOneByIdAxlesQueryHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private AxlesRepositoryInterface $axlesRepositoryInterface
    ) {}

    public function handler(AxlesQuery $axlesQuery): ?Axles
    {

        $id = $axlesQuery->getId();
        $this->inputErrorsPartNumbers->emptyData($id);
        $participant = $axlesQuery->getIdParticipant();
        $this->inputErrorsPartNumbers->userIsNotIdentified($participant);

        $edit_axle = $this->axlesRepositoryInterface->findOneByIdAxles($id, $participant);
        $this->inputErrorsPartNumbers->emptyEntity($edit_axle);

        return $edit_axle;
    }
}
