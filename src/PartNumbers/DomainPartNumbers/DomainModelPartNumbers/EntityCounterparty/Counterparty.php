<?php

namespace App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty;

use Doctrine\ORM\Mapping as ORM;
use App\Counterparty\InfrastructureCounterparty\RepositoryCounterparty\CounterpartyRepository;

#[ORM\Entity(repositoryClass: CounterpartyRepository::class)]
class Counterparty
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 28, nullable: true)]
    private ?string $name_counterparty = null;

    #[ORM\Column(length: 48, nullable: true)]
    private ?string $mail_counterparty = null;

    #[ORM\Column(length: 13, nullable: true)]
    private ?string $manager_phone = null;

    #[ORM\Column(length: 13, nullable: true)]
    private ?string $delivery_phone = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameCounterparty(): ?string
    {
        return $this->name_counterparty;
    }

    public function setNameCounterparty(?string $name_counterparty): static
    {
        $this->name_counterparty = $name_counterparty;

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

    public function getManagerPhone(): ?string
    {
        return $this->manager_phone;
    }

    public function setManagerPhone(?string $manager_phone): static
    {
        $this->manager_phone = $manager_phone;

        return $this;
    }

    public function getDeliveryPhone(): ?string
    {
        return $this->delivery_phone;
    }

    public function setDeliveryPhone(?string $delivery_phone): static
    {
        $this->delivery_phone = $delivery_phone;

        return $this;
    }
}
