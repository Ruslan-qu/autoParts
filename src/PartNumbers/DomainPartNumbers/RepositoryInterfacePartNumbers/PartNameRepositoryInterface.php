<?php

namespace App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartName;

interface  PartNameRepositoryInterface
{
    public function save(PartName $partName): int;

    public function numberDoubles(array $array): int;

    public function findOneByPartName(string $part_name, Participant $id_participant): ?PartName;

    public function countId(): ?array;

    public function findByPartName(Participant $id_participant): ?array;

    public function findPartName(int $id): ?PartName;
}
