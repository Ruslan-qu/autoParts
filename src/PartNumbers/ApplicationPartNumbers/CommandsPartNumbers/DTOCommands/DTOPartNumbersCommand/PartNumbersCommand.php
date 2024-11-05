<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOPartNumbersCommand;

use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Axles;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Sides;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Bodies;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartName;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\CarBrands;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Availability;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\OriginalRooms;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOPartNumbersCommand\MapPartNumbersCommand;

final class PartNumbersCommand extends MapPartNumbersCommand
{
    protected ?int $id = null;

    protected ?string $part_number = null;

    protected ?string $manufacturer = null;

    protected ?string $additional_descriptions = null;

    protected ?PartName $id_part_name = null;

    protected ?CarBrands $id_car_brand = null;

    protected ?Sides $id_side = null;

    protected ?Bodies $id_body = null;

    protected ?Axles $id_axle = null;

    protected ?Availability $id_in_stock = null;

    protected ?OriginalRooms $id_original_number = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPartNumber(): ?string
    {
        return $this->part_number;
    }

    public function getManufacturer(): ?string
    {
        return $this->manufacturer;
    }

    public function getAdditionalDescriptions(): ?string
    {
        return $this->additional_descriptions;
    }

    public function getIdPartName(): ?PartName
    {
        return $this->id_part_name;
    }

    public function getIdCarBrand(): ?CarBrands
    {
        return $this->id_car_brand;
    }

    public function getIdSide(): ?Sides
    {
        return $this->id_side;
    }

    public function getIdBody(): ?Bodies
    {
        return $this->id_body;
    }

    public function getIdAxle(): ?Axles
    {
        return $this->id_axle;
    }

    public function getIdInStock(): ?Availability
    {
        return $this->id_in_stock;
    }

    public function getIdOriginalNumber(): ?OriginalRooms
    {
        return $this->id_original_number;
    }
}
