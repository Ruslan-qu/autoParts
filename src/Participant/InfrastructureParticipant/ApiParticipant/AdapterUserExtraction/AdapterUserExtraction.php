<?php

namespace App\Participant\InfrastructureParticipant\ApiParticipant\AdapterUserExtraction;

use Symfony\Bundle\SecurityBundle\Security;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\Participant\DomainParticipant\AdaptersInterface\AdapterUserExtractionInterface;

class AdapterUserExtraction implements AdapterUserExtractionInterface
{

    public function __construct(
        private Security $security
    ) {}

    public function userExtraction(): ?Participant
    {

        return $this->security->getUser();
    }
}
