<?php

namespace App\Participant\DomainParticipant\RepositoryInterfaceParticipant;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;

interface  ParticipantRepositoryInterface
{
    public function save(Participant $participant): int;

    public function edit(Participant $participant): int;

    public function delete(Participant $participant): int;

    public function numberDoubles(array $array): int;

    public function findAllParticipant(): ?array;

    public function findParticipant($id): ?Participant;

    public function findOneByParticipant(string $email): ?Participant;
}
