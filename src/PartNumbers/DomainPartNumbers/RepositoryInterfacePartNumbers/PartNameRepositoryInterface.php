<?php

namespace App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers;

use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartName;

interface  PartNameRepositoryInterface
{
    public function save(PartName $partName): int;

    public function numberDoubles(array $array): int;

    public function findOneByPartName(string $part_name): ?PartName;
}
