<?php

namespace App\PartNumbers\ApplicationPartNumbers\QuerySides\SearchSidesQuery;

use App\PartNumbers\ApplicationPartNumbers\QuerySides\DTOQuery\DTOSidesQuery\SidesQuery;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\SidesRepositoryInterface;

final class FindBySidesQueryHandler
{

    public function __construct(
        private SidesRepositoryInterface $sidesRepositoryInterface
    ) {}

    public function handler(SidesQuery $sidesQuery): ?array
    {
        $id_participant = $sidesQuery->getIdParticipant();
        $findAllSides = $this->sidesRepositoryInterface
            ->findBySides($id_participant);

        return $findAllSides;
    }
}
