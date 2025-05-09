<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryPartNames\SearchPartNameQuery;

use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\PartNameRepositoryInterface;

final class FindAllPartNameQueryHandler
{

    public function __construct(
        private PartNameRepositoryInterface $partNameRepositoryInterface
    ) {}

    public function handler(): ?array
    {
        $findAllPartName = $this->partNameRepositoryInterface->findAllPartName();

        return $findAllPartName;
    }
}
