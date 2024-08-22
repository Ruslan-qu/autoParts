<?php

namespace App\Counterparty\ApplicationCounterparty\QueryCounterparty\SearchCounterpartyQuery\FindCounterparty;

use App\Counterparty\ApplicationCounterparty\QueryCounterparty\SearchCounterpartyQuery\SearchCounterpartyQuery;

final class CreateSearchCounterpartyQuery extends SearchCounterpartyQuery
{
    protected ?string $name_counterparty = null;

    protected ?string $mail_counterparty = null;

    public function getNameCounterparty(): ?string
    {
        return $this->name_counterparty;
    }

    public function getMailCounterparty(): ?string
    {
        return $this->mail_counterparty;
    }
}
