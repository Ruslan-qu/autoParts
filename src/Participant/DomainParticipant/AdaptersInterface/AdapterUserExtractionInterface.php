<?php

namespace App\Participant\DomainParticipant\AdaptersInterface;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;


interface AdapterUserExtractionInterface
{
    public function userExtraction(): ?Participant;
}
