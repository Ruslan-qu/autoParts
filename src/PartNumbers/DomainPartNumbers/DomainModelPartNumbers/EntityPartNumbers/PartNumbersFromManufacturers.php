<?php

namespace App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Axles;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Sides;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Bodies;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartName;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\CarBrands;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Availability;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\OriginalRooms;
use App\PartNumbers\InfrastructurePartNumbers\RepositoryPartNumbers\PartNumbersFromManufacturersRepository;

#[ORM\Entity(repositoryClass: PartNumbersFromManufacturersRepository::class)]
class PartNumbersFromManufacturers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 23, nullable: true)]
    private ?string $part_number = null;

    #[ORM\Column(length: 23, nullable: true)]
    private ?string $manufacturer = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $additional_descriptions = null;

    #[ORM\ManyToOne]
    private ?PartName $id_part_name = null;

    #[ORM\ManyToOne]
    private ?CarBrands $id_car_brand = null;

    #[ORM\ManyToOne]
    private ?Sides $id_side = null;

    #[ORM\ManyToOne]
    private ?Bodies $id_body = null;

    #[ORM\ManyToOne]
    private ?Axles $id_axle = null;

    #[ORM\ManyToOne]
    private ?Availability $id_in_stock = null;

    #[ORM\ManyToOne]
    private ?OriginalRooms $id_original_number = null;

    public function getId(): ?int
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

    public function getIdPartName(): ?PartName
    {
        return $this->id_part_name;
    }

    public function setIdPartName(?PartName $id_part_name): static
    {
        $this->id_part_name = $id_part_name;

        return $this;
    }

    public function getIdCarBrand(): ?CarBrands
    {
        return $this->id_car_brand;
    }

    public function setIdCarBrand(?CarBrands $id_car_brand): static
    {
        $this->id_car_brand = $id_car_brand;

        return $this;
    }

    public function getIdSide(): ?Sides
    {
        return $this->id_side;
    }

    public function setIdSide(?Sides $id_side): static
    {
        $this->id_side = $id_side;

        return $this;
    }

    public function getIdBody(): ?Bodies
    {
        return $this->id_body;
    }

    public function setIdBody(?Bodies $id_body): static
    {
        $this->id_body = $id_body;

        return $this;
    }

    public function getIdAxle(): ?Axles
    {
        return $this->id_axle;
    }

    public function setIdAxle(?Axles $id_axle): static
    {
        $this->id_axle = $id_axle;

        return $this;
    }

    public function getIdInStock(): ?Availability
    {
        return $this->id_in_stock;
    }

    public function setIdInStock(?Availability $id_in_stock): static
    {
        $this->id_in_stock = $id_in_stock;

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
}
