<?php

namespace App\Counterparty\DomainCounterparty\AdaptersInterface;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;

interface AdapterAutoPartsWarehouseCounterpartyInterface
{
    public function emailCounterpartySearch(array $emails_counterparty): ?array;

    public function counterpartySearch(array $arr_counterparty): ?array;

    public function findByCounterparty(Participant $participant): ?array;
}
