<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingApi\DTOAutoPartsApi;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingApi\DTOAutoPartsApi\MapApiAutoParts;


final class ApiAutoParts extends MapApiAutoParts
{
    protected ?Counterparty $id_counterparty = null;

    public function getIdCounterparty(): ?Counterparty
    {
        return $this->id_counterparty;
    }
}
