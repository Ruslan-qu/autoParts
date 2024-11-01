<?php

namespace App\Sales\ApplicationSales\CommandsSales\DTOAutoPartsSoldCommand;

use App\Sales\ApplicationSales\CommandsSales\DTOAutoPartsSoldCommand\MapAutoPartsSoldCommand;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse;

final class AutoPartsSoldCommand extends MapAutoPartsSoldCommand

{
    protected ?int $id = null;

    protected ?AutoPartsWarehouse $id_auto_parts_warehouse = null;

    protected ?int $quantity_sold = null;

    protected ?int $price_sold = null;

    protected ?\DateTimeImmutable $date_sold = null;

    protected ?int $id_sold = null;

    protected ?bool $sold_status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdAutoPartsWarehouse(): ?AutoPartsWarehouse
    {
        return $this->id_auto_parts_warehouse;
    }

    public function getQuantitySold(): ?int
    {
        return $this->quantity_sold;
    }

    public function getPriceSold(): ?int
    {
        return $this->price_sold;
    }

    public function getDateSold(): ?\DateTimeImmutable
    {
        return $this->date_sold;
    }

    public function getIdSold(): ?int
    {
        return $this->id_sold;
    }

    public function isSoldStatus(): ?bool
    {
        return $this->sold_status;
    }
}
