<?php

namespace App\Counterparty\ApplicationCounterparty\CommandsCounterparty\DTOCommands;

use App\Counterparty\ApplicationCounterparty\CommandsCounterparty\DTOCommands\CounterpartyCommand;


final class CreateCounterpartyCommand extends CounterpartyCommand
{
    protected ?int $id = null;

    protected ?string $name_counterparty = null;

    protected ?string $mail_counterparty = null;

    protected ?string $manager_phone = null;

    protected ?string $delivery_phone = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameCounterparty(): ?string
    {
        return $this->name_counterparty;
    }

    public function getMailCounterparty(): ?string
    {
        return $this->mail_counterparty;
    }

    public function getManagerPhone(): ?string
    {
        return $this->manager_phone;
    }

    public function getDeliveryPhone(): ?string
    {
        return $this->delivery_phone;
    }
}
