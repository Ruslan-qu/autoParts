<?php

namespace App\Entity\PartNumbersFromManufacturersEntity;

use App\Repository\OriginalRoomsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OriginalRoomsRepository::class)]
class OriginalRooms
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 48, nullable: true)]
    private ?string $original_number = null;

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
}