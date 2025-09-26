<?php

namespace App\Participant\ApplicationParticipant\QueryParticipant\SearchParticipantQuery;

use Symfony\Bundle\SecurityBundle\Security;
use App\Participant\DomainParticipant\RepositoryInterfaceParticipant\ParticipantRepositoryInterface;

final class FindByParticipantQueryHandler
{

    public function __construct(
        private ParticipantRepositoryInterface $participantRepositoryInterface,
        private Security $security
    ) {}

    public function handler(): ?array
    {
        //dd($this->security->getUser()->getRoles());
        // dd($this->security->isGranted('ROLE_ADMIN'));
        $participant = $this->participantRepositoryInterface->findByParticipant();
        dd($participant);
        return [];
    }
}
