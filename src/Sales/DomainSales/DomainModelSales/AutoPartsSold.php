<?php

namespace App\Sales\DomainSales\DomainModelSales;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Sales\InfrastructureSales\RepositorySales\AutoPartsSoldRepository;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse;

#[ORM\Entity(repositoryClass: AutoPartsSoldRepository::class)]
class AutoPartsSold
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?AutoPartsWarehouse $id_auto_parts_warehouse = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantity_sold = null;

    #[ORM\Column(nullable: true)]
    private ?int $price_sold = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $date_sold = null;

    #[ORM\Column(nullable: true)]
    private ?int $id_sold = null;

    #[ORM\Column(nullable: true)]
    private ?bool $sold_status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdAutoPartsWarehouse(): ?AutoPartsWarehouse
    {
        return $this->id_auto_parts_warehouse;
    }

    public function setIdAutoPartsWarehouse(?AutoPartsWarehouse $id_auto_parts_warehouse): static
    {
        $this->id_auto_parts_warehouse = $id_auto_parts_warehouse;

        return $this;
    }

    public function getQuantitySold(): ?int
    {
        return $this->quantity_sold;
    }

    public function setQuantitySold(?int $quantity_sold): static
    {
        $this->quantity_sold = $quantity_sold;

        return $this;
    }

    public function getPriceSold(): ?int
    {
        return $this->price_sold;
    }

    public function setPriceSold(?int $price_sold): static
    {
        $this->price_sold = $price_sold;

        return $this;
    }

    public function getDateSold(): ?\DateTimeImmutable
    {
        return $this->date_sold;
    }

    public function setDateSold(?\DateTimeImmutable $date_sold): static
    {
        $this->date_sold = $date_sold;

        return $this;
    }

    public function getIdSold(): ?int
    {
        return $this->id_sold;
    }

    public function setIdSold(?int $id_sold): static
    {
        $this->id_sold = $id_sold;

        return $this;
    }

    public function isSoldStatus(): ?bool
    {
        return $this->sold_status;
    }

    public function setSoldStatus(?bool $sold_status): static
    {
        $this->sold_status = $sold_status;

        return $this;
    }
}
