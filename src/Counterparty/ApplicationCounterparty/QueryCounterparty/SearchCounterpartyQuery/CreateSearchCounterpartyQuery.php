<?php

namespace App\Counterparty\ApplicationCounterparty\QueryCounterparty\SearchCounterpartyQuery;

use App\Counterparty\ApplicationCounterparty\QueryCounterparty\SearchCounterpartyQuery\SearchCounterpartyQuery;

final class CreateSearchCounterpartyQuery extends SearchCounterpartyQuery
{
    protected ?string $name_counterparty = null;


    public function getNameCounterparty(): ?string
    {
        return $this->name_counterparty;
    }
}
