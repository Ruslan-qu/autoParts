<?php

namespace App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use Doctrine\ORM\Mapping as ORM;
use App\PartNumbers\InfrastructurePartNumbers\RepositoryPartNumbers\OriginalRoomsRepository;

#[ORM\Entity(repositoryClass: OriginalRoomsRepository::class)]
class OriginalRooms
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 48, nullable: true)]
    private ?string $original_number = null;

    #[ORM\Column(length: 45, nullable: true)]
    private ?string $original_manufacturer = null;

    #[ORM\ManyToOne]
    private ?Participant $id_participant = null;

    #[ORM\Column(nullable: true)]
    private ?array $replacing_original_number = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOriginalNumber(): ?string
    {
        return $this->original_number;
    }

    public function setOriginalNumber(?string $original_number): static
    {
        $this->original_number = $original_number;

        return $this;
    }

    public function getOriginalManufacturer(): ?string
    {
        return $this->original_manufacturer;
    }

    public function setOriginalManufacturer(?string $original_manufacturer): static
    {
        $this->original_manufacturer = $original_manufacturer;

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

    public function getReplacingOriginalNumber(): ?array
    {
        return $this->replacing_original_number;
    }

    public function setReplacingOriginalNumber(?array $replacing_original_number): static
    {
        $this->replacing_original_number = $replacing_original_number;

        return $this;
    }
}
