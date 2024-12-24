<?php

namespace App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers;

use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartName;

interface  PartNameRepositoryInterface
{
    public function findOneByPartName(string $part_name): ?PartName;
}
