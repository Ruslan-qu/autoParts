<?php

namespace App\Counterparty\ApplicationCounterparty\CommandsSaveEditDeleteCounterparty;

use Symfony\Component\Mime\Address;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateSaveCounterpartyCommand extends CreateCounterpartyCommand
{
    #[Assert\NotBlank]
    #[Assert\Type("string")]
    #[Assert\Regex('/^[\da-z]*$/i')]
    protected ?string $counterparty = null;

    #[Assert\NotBlank]
    #[Assert\Type("Address::class")]
    #[Assert\Email]
    protected ?string $mail_counterparty = null;

    public function getCounterparty(): ?string
    {
        return $this->counterparty;
    }

    public function getMailCounterparty(): ?string
    {
        return $this->mail_counterparty;
    }
}
