<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryBodies\SearchBodiesQuery;

use App\PartNumbers\ApplicationPartNumbers\QueryBodies\DTOQuery\DTOBodiesQuery\BodiesQuery;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\BodiesRepositoryInterface;

final class FindByBodiesQueryHandler
{

    public function __construct(
        private BodiesRepositoryInterface $bodiesRepositoryInterface
    ) {}

    public function handler(BodiesQuery $bodiesQuery): ?array
    {
        $id_participant = $bodiesQuery->getIdParticipant();
        $findAllBodies = $this->bodiesRepositoryInterface
            ->findByBodies($id_participant);

        return $findAllBodies;
    }
}
