<?php

namespace App\Entity\PartNumbersFromManufacturersEntity;

use App\Repository\PartNumbersFromManufacturersRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PartNumbersFromManufacturersRepository::class)]
class PartNumbersFromManufacturers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 23, nullable: true)]
    private ?string $part_number = null;

    #[ORM\Column(length: 23, nullable: true)]
    private ?string $manufacturer = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $additional_descriptions = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getPartNumber(): ?string
    {
        return $this->part_number;
    }

    public function setPartNumber(?string $part_number): static
    {
        $this->part_number = $part_number;

        return $this;
    }

    public function getManufacturer(): ?string
    {
        return $this->manufacturer;
    }

    public function setManufacturer(?string $manufacturer): static
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    public function getAdditionalDescriptions(): ?string
    {
        return $this->additional_descriptions;
    }

    public function setAdditionalDescriptions(?string $additional_descriptions): static
    {
        $this->additional_descriptions = $additional_descriptions;

        return $this;
    }
}
