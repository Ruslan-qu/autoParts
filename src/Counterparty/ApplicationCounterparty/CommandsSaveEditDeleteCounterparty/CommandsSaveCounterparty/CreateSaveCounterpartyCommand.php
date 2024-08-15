<?php

namespace App\Counterparty\ApplicationCounterparty\CommandsSaveEditDeleteCounterparty\CommandsSaveCounterparty;

final class CreateSaveCounterpartyCommand extends CreateCounterpartyCommand
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
