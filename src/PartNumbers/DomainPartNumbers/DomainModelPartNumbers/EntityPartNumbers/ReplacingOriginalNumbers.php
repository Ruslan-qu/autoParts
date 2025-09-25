<?php

namespace App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use Doctrine\ORM\Mapping as ORM;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\OriginalRooms;
use App\PartNumbers\InfrastructurePartNumbers\RepositoryPartNumbers\ReplacingOriginalNumbersRepository;

#[ORM\Entity(repositoryClass: ReplacingOriginalNumbersRepository::class)]
class ReplacingOriginalNumbers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 33, nullable: true)]
    private ?string $replacing_original_number = null;

    #[ORM\ManyToOne]
    private ?OriginalRooms $id_original_number = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(onDelete: 'Cascade')]
    private ?Participant $id_participant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReplacingOriginalNumber(): ?string
    {
        return $this->replacing_original_number;
    }

    public function setReplacingOriginalNumber(?string $replacing_original_number): static
    {
        $this->replacing_original_number = $replacing_original_number;

        return $this;
    }

    public function getIdOriginalNumber(): ?OriginalRooms
    {
        return $this->id_original_number;
    }

    public function setIdOriginalNumber(?OriginalRooms $id_original_number): static
    {
        $this->id_original_number = $id_original_number;

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
