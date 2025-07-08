<?php

namespace App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Axles;

interface  AxlesRepositoryInterface
{
    public function save(Axles $axles): int;

    public function edit(Axles $axles): array;

    public function delete(Axles $axles): ?array;

    public function numberDoubles(array $array): int;

    public function findOneByAxles(string $axles, Participant $id_participant): ?Axles;

    public function findOneByIdAxles(int $id, Participant $id_participant): ?Axles;

    public function countId(): ?array;

    public function findByAxles(Participant $id_participant): ?array;

    public function findAxles(int $id): ?Axles;
}
