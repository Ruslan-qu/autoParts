<?php

namespace App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use Doctrine\ORM\Mapping as ORM;
use App\PartNumbers\InfrastructurePartNumbers\RepositoryPartNumbers\PartNameRepository;

#[ORM\Entity(repositoryClass: PartNameRepository::class)]
class PartName
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 48, nullable: true)]
    private ?string $part_name = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'Cascade')]
    private ?Participant $id_participant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPartName(): ?string
    {
        return $this->part_name;
    }

    public function setPartName(?string $part_name): static
    {
        $this->part_name = $part_name;

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
