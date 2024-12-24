<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\SearchPartNumbersQuery;

use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartName;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\PartNameRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\DTOQuery\DTOPartNameQuery\PartNameQuery;

final class FindOneByPartNameQueryHandler
{

    public function __construct(
        private PartNameRepositoryInterface $partNameRepositoryInterface
    ) {}

    public function handler(PartNameQuery $partNameQuery): ?PartName
    {

        $part_name = strtolower(preg_replace(
            '#\s#',
            '',
            $partNameQuery->getPartName()
        ));

        $findOneByPartName = $this->partNameRepositoryInterface->findOneByPartName($part_name);

        return $findOneByPartName;
    }
}
