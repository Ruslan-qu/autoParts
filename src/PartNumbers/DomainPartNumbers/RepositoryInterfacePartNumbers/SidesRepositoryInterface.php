<?php

namespace App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Sides;

interface  SidesRepositoryInterface
{
    public function save(Sides $sides): int;

    public function edit(Sides $sides): array;

    public function delete(Sides $sides): ?array;

    public function numberDoubles(array $array): int;

    public function findOneBySides(string $sides, Participant $id_participant): ?Sides;

    public function findOneByIdSides(int $id, Participant $id_participant): ?Sides;

    public function countId(): ?array;

    public function findBySides(Participant $id_participant): ?array;

    public function findSides(int $id): ?Sides;
}
