<?php

namespace App\Participant\ApplicationParticipant\QueryParticipant\SearchParticipantQuery;

use Symfony\Bundle\SecurityBundle\Security;
use App\Participant\DomainParticipant\RepositoryInterfaceParticipant\ParticipantRepositoryInterface;

final class FindAllParticipantQueryHandler
{

    public function __construct(
        private ParticipantRepositoryInterface $participantRepositoryInterface,
        private Security $security
    ) {}

    public function handler(): ?array
    {
        $participants = $this->participantRepositoryInterface->findAllParticipant();
        $participants = $this->unsetParticipant($participants);

        return $participants;
    }

    private function unsetParticipant(array $participants): ?array
    {
        if ($this->security->isGranted('ROLE_ADMIN') && !$this->security->isGranted('ROLE_BOSS')) {
            foreach ($participants as $key => $value) {

                if (in_array('ROLE_ADMIN', $value->getRoles(), true)) {
                    unset($participants[$key]);
                }
                if (in_array('ROLE_BOSS', $value->getRoles(), true)) {
                    unset($participants[$key]);
                }
            }
        }

        return $participants;
    }
}
