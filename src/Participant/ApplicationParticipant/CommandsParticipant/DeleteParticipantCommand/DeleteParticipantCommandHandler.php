<?php

namespace App\Participant\ApplicationParticipant\CommandsParticipant\DeleteParticipantCommand;

use App\Participant\DomainParticipant\RepositoryInterfaceParticipant\ParticipantRepositoryInterface;
use App\Participant\ApplicationParticipant\CommandsParticipant\DTOCommands\DTOParticipantObjCommand\ParticipantObjCommand;

final class DeleteParticipantCommandHandler
{

    public function __construct(
        private ParticipantRepositoryInterface $participantRepositoryInterface,
    ) {}

    public function handler(ParticipantObjCommand $participantObjCommand): int
    {
        $participant = $participantObjCommand->getParticipant();

        return $this->participantRepositoryInterface->delete($participant);
    }
}
