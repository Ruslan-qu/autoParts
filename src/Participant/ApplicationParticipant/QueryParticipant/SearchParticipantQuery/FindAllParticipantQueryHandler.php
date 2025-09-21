<?php

namespace App\Participant\ApplicationParticipant\QueryParticipant\SearchParticipantQuery;

use App\Participant\DomainParticipant\RepositoryInterfaceParticipant\ParticipantRepositoryInterface;

final class FindAllParticipantQueryHandler
{

    public function __construct(
        private ParticipantRepositoryInterface $participantRepositoryInterface
    ) {}

    public function handler(): ?array
    {

        return $this->participantRepositoryInterface->findAllParticipant();
    }
}
