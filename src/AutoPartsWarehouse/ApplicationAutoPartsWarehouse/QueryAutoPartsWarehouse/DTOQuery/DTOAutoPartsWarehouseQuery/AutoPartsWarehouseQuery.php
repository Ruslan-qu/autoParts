<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOAutoPartsWarehouseQuery;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Axles;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Sides;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Bodies;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartName;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\CarBrands;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOAutoPartsWarehouseQuery\MapAutoPartsWarehouseQuery;

final class AutoPartsWarehouseQuery extends MapAutoPartsWarehouseQuery

{
    protected ?int $id = null;

    protected ?PartName $id_part_name = null;

    protected ?CarBrands $id_car_brand = null;

    protected ?Sides $id_side = null;

    protected ?Bodies $id_body = null;

    protected ?Axles $id_axle = null;

    protected ?Participant $id_participant = null;

    protected bool $is_customer_order = false;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIdParticipant(): ?Participant
    {
        return $this->id_participant;
    }

    public function isCustomerOrder(): bool
    {
        return $this->is_customer_order;
    }
}
