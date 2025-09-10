<?php

namespace App\Counterparty\DomainCounterparty\AdaptersInterface;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;

interface AdapterAutoPartsWarehouseCounterpartyInterface
{
    public function counterpartySearch(array $arr_counterparty): ?array;

    public function findByCounterparty(Participant $participant): ?array;
}
