<?php

namespace App\Entity\PartNumbersFromManufacturersEntity;

use App\Repository\PartNameRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PartNameRepository::class)]
class PartName
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 48, nullable: true)]
    private ?string $part_name = null;

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
}
