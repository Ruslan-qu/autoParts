<?php

namespace App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Bodies;

interface  BodiesRepositoryInterface
{
    public function save(Bodies $bodies): int;

    public function edit(Bodies $bodies): array;

    public function delete(Bodies $bodies): ?array;

    public function numberDoubles(array $array): int;

    public function findOneByBodies(string $bodies, Participant $id_participant): ?Bodies;

    public function findOneByIdBodies(int $id, Participant $id_participant): ?Bodies;

    public function countId(): ?array;

    public function findByBodies(Participant $id_participant): ?array;

    public function findBodies(int $id): ?Bodies;
}
