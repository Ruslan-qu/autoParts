<?php

namespace App\Entity\PartNumbersFromManufacturersEntity;

use App\Repository\AxlesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AxlesRepository::class)]
class Axles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 12, nullable: true)]
    private ?string $axle = null;

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
}
