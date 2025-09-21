<?php

namespace App\Participant\ApplicationParticipant\CommandsParticipant\DTOCommands\DTOParticipantObjCommand;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\Participant\ApplicationParticipant\CommandsParticipant\DTOCommands\DTOParticipantObjCommand\MapParticipantObjCommand;


final class ParticipantObjCommand extends MapParticipantObjCommand
{
    protected ?Participant $participant = null;

    public function getParticipant(): ?Participant
    {
        return $this->participant;
    }
}
