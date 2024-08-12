<?php

namespace App\Entity\PartNumbersFromManufacturersEntity;

use App\Repository\SidesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SidesRepository::class)]
class Sides
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 28, nullable: true)]
    private ?string $side = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSide(): ?string
    {
        return $this->side;
    }

    public function setSide(?string $side): static
    {
        $this->side = $side;

        return $this;
    }
}
