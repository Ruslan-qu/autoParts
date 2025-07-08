<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryAxles\SearchAxlesQuery;

use App\PartNumbers\ApplicationPartNumbers\QueryAxles\DTOQuery\DTOAxlesQuery\AxlesQuery;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\AxlesRepositoryInterface;

final class FindByAxlesQueryHandler
{

    public function __construct(
        private AxlesRepositoryInterface $axlesRepositoryInterface
    ) {}

    public function handler(AxlesQuery $axlesQuery): ?array
    {
        $id_participant = $axlesQuery->getIdParticipant();
        $findAllAxles = $this->axlesRepositoryInterface
            ->findByAxles($id_participant);

        return $findAllAxles;
    }
}
