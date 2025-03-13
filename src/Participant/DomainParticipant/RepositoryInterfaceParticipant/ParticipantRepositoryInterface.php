<?php

namespace App\Participant\DomainParticipant\RepositoryInterfaceParticipant;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;

interface  ParticipantRepositoryInterface
{
    public function save(Participant $participant): array;

    public function numberDoubles(array $array): int;
}
