<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryPartNames\SearchPartNameQuery;

use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\PartNameRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNames\DTOQuery\DTOPartNameQuery\PartNameQuery;

final class FindByPartNameQueryHandler
{

    public function __construct(
        private PartNameRepositoryInterface $partNameRepositoryInterface
    ) {}

    public function handler(PartNameQuery $PartNameQuery): ?array
    {
        $id_participant = $PartNameQuery->getIdParticipant();
        $findAllPartName = $this->partNameRepositoryInterface
            ->findByPartName($id_participant);

        return $findAllPartName;
    }
}
