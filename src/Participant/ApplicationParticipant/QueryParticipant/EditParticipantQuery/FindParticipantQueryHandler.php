<?php

namespace App\Participant\ApplicationParticipant\QueryParticipant\EditParticipantQuery;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\Participant\ApplicationParticipant\ErrorsParticipant\InputErrorsParticipant;
use App\Participant\DomainParticipant\RepositoryInterfaceParticipant\ParticipantRepositoryInterface;
use App\Participant\ApplicationParticipant\QueryParticipant\DTOQuery\DTOParticipantQuery\ParticipantQuery;

final class FindParticipantQueryHandler
{

    public function __construct(
        private InputErrorsParticipant $inputErrorsParticipant,
        private ParticipantRepositoryInterface $participantRepositoryInterface
    ) {}

    public function handler(ParticipantQuery $participantQuery): ?Participant
    {
        $id = $participantQuery->getId();
        $this->inputErrorsParticipant->emptyData($id);

        $edit_counterparty = $this->participantRepositoryInterface->findParticipant($id);
        $this->inputErrorsParticipant->emptyEntity($edit_counterparty);

        return $edit_counterparty;
    }
}
