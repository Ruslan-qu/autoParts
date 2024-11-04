<?php

namespace App\Sales\ApplicationSales\QuerySales\DTOSales\DTOSalesQuery;

use App\Sales\ApplicationSales\QuerySales\DTOSales\DTOSalesQuery\MapSalesQuery;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Axles;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Sides;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Bodies;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartName;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\CarBrands;
use App\Sales\ApplicationSales\QuerySales\DTOSales\DTOAutoPartsSoldQuery\MapAutoPartsSoldQuery;
use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse;

final class SalesQuery extends MapSalesQuery

{
    protected ?string $original_number = null;

    protected ?string $part_number = null;

    protected ?\DateTimeImmutable $from_date_sold = null;

    protected ?\DateTimeImmutable $to_date_sold = null;

    protected ?PartName $id_part_name = null;

    protected ?CarBrands $id_car_brand = null;

    protected ?Sides $id_side = null;

    protected ?Bodies $id_body = null;

    protected ?Axles $id_axle = null;

    protected ?Counterparty $id_counterparty = null;

    public function getFromDateSold(): ?\DateTimeImmutable
    {
        return $this->from_date_sold;
    }

    public function getToDateSold(): ?\DateTimeImmutable
    {
        return $this->to_date_sold;
    }

    public function getOriginalNumber(): ?string
    {
        return $this->original_number;
    }

    public function getPartNumber(): ?string
    {
        return $this->part_number;
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

    public function getIdCounterparty(): ?Counterparty
    {
        return $this->id_counterparty;
    }
}
