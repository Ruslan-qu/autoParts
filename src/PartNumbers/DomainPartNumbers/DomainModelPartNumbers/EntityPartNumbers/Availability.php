<?php

namespace App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use Doctrine\ORM\Mapping as ORM;
use App\PartNumbers\InfrastructurePartNumbers\RepositoryPartNumbers\AvailabilityRepository;

#[ORM\Entity(repositoryClass: AvailabilityRepository::class)]
class Availability
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 28, nullable: true)]
    private ?string $in_stock = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'Cascade')]
    private ?Participant $id_participant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInStock(): ?string
    {
        return $this->in_stock;
    }

    public function setInStock(?string $in_stock): static
    {
        $this->in_stock = $in_stock;

        return $this;
    }

    public function getIdParticipant(): ?Participant
    {
        return $this->id_participant;
    }

    public function setIdParticipant(?Participant $id_participant): static
    {
        $this->id_participant = $id_participant;

        return $this;
    }
}
