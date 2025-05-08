<?php

namespace App\Participant\ApplicationParticipant\QueryParticipant\UserExtractionQuery;

use Symfony\Bundle\SecurityBundle\Security;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;

final class UserExtractionQueryHandler
{

    public function __construct(
        private Security $security
    ) {}

    public function handler(): ?Participant
    {

        return $this->security->getUser();
    }
}
