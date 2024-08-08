<?php

namespace App\Entity;

use App\Repository\CounterpartyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CounterpartyRepository::class)]
class Counterparty
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 28, nullable: true)]
    private ?string $counterparty = null;

    #[ORM\Column(length: 48, nullable: true)]
    private ?string $mail_counterparty = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCounterparty(): ?string
    {
        return $this->counterparty;
    }

    public function setCounterparty(?string $counterparty): static
    {
        $this->counterparty = $counterparty;

        return $this;
    }

    public function getMailCounterparty(): ?string
    {
        return $this->mail_counterparty;
    }

    public function setMailCounterparty(?string $mail_counterparty): static
    {
        $this->mail_counterparty = $mail_counterparty;

        return $this;
    }
}
