<?php

namespace App\Entity\PartNumbersFromManufacturersEntity;

use App\Repository\CarBrandsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarBrandsRepository::class)]
class CarBrands
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 24, nullable: true)]
    private ?string $car_brand = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarBrand(): ?string
    {
        return $this->car_brand;
    }

    public function setCarBrand(?string $car_brand): static
    {
        $this->car_brand = $car_brand;

        return $this;
    }
}
