<?php

namespace App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartNumbersFromManufacturers;
use App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\RepositoryAutoPartsWarehouse\AutoPartsWarehouseRepository;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\PaymentMethod;

#[ORM\Entity(repositoryClass: AutoPartsWarehouseRepository::class)]
class AutoPartsWarehouse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantity = null;

    #[ORM\Column(nullable: true)]
    private ?int $price = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantity_sold = null;

    #[ORM\Column(nullable: true)]
    private ?int $sales = null;

    #[ORM\ManyToOne]
    private ?Counterparty $id_counterparty = null;

    #[ORM\ManyToOne]
    private ?PartNumbersFromManufacturers $id_details = null;

    #[ORM\ManyToOne]
    private ?PaymentMethod $id_payment_method = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $date_receipt_auto_parts_warehouse = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): static
    {
        $this->price = $price;

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

    public function getSales(): ?int
    {
        return $this->sales;
    }

    public function setSales(?int $sales): static
    {
        $this->sales = $sales;

        return $this;
    }

    public function getIdCounterparty(): ?Counterparty
    {
        return $this->id_counterparty;
    }

    public function setIdCounterparty(?Counterparty $id_counterparty): static
    {
        $this->id_counterparty = $id_counterparty;

        return $this;
    }

    public function getIdDetails(): ?PartNumbersFromManufacturers
    {
        return $this->id_details;
    }

    public function setIdDetails(?PartNumbersFromManufacturers $id_details): static
    {
        $this->id_details = $id_details;

        return $this;
    }

    public function getIdPaymentMethod(): ?PaymentMethod
    {
        return $this->id_payment_method;
    }

    public function setIdPaymentMethod(?PaymentMethod $id_payment_method): static
    {
        $this->id_payment_method = $id_payment_method;

        return $this;
    }

    public function getDateReceiptAutoPartsWarehouse(): ?\DateTimeImmutable
    {
        return $this->date_receipt_auto_parts_warehouse;
    }

    public function setDateReceiptAutoPartsWarehouse(?\DateTimeImmutable $date_receipt_auto_parts_warehouse): static
    {
        $this->date_receipt_auto_parts_warehouse = $date_receipt_auto_parts_warehouse;

        return $this;
    }
}
