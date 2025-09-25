<?php

namespace App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use Doctrine\ORM\Mapping as ORM;
use App\PartNumbers\InfrastructurePartNumbers\RepositoryPartNumbers\AxlesRepository;

#[ORM\Entity(repositoryClass: AxlesRepository::class)]
class Axles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $axle = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'Cascade')]
    private ?Participant $id_participant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAxle(): ?string
    {
        return $this->axle;
    }

    public function setAxle(?string $axle): static
    {
        $this->axle = $axle;

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
