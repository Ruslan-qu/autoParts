<?php

namespace App\Participant\InfrastructureParticipant\ApiParticipant\AdapterUserExtraction;

use Symfony\Bundle\SecurityBundle\Security;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\Participant\DomainParticipant\AdaptersInterface\AdapterUserExtractionInterface;
use App\PartNumbers\InfrastructurePartNumbers\ErrorMessageViaSession\ErrorMessageViaSession;

class AdapterUserExtraction implements AdapterUserExtractionInterface
{

    public function __construct(
        private Security $security,
        private InputErrorsPartNumbers $inputErrorsPartNumbers
    ) {}

    public function userExtraction(): ?Participant
    {

        $participant = $this->security->getUser();
        $this->inputErrorsPartNumbers->emptyData($participant);
        return $participant;
    }
}
