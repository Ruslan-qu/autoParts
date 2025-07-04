<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryBodies\DeleteBodiesQuery;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\ApplicationPartNumbers\QueryBodies\DTOQuery\DTOBodiesQuery\BodiesQuery;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\BodiesRepositoryInterface;

final class FindBodiesQueryHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private BodiesRepositoryInterface $bodiesRepositoryInterface
    ) {}

    public function handler(BodiesQuery $bodiesQuery): ?array
    {
        $id = $bodiesQuery->getId();
        $this->inputErrorsPartNumbers->emptyData($id);

        $find_bodies['bodies'] = $this->bodiesRepositoryInterface->findBodies($id);
        $this->inputErrorsPartNumbers->emptyEntity($find_bodies);

        return $find_bodies;
    }
}
