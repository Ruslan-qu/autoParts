<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DTOCommands\DTOAutoPartsWarehouseCommand;

use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartNumbersFromManufacturers;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\PaymentMethod;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DTOCommands\DTOAutoPartsWarehouseCommand\MapAutoPartsWarehouseCommand;

final class AutoPartsWarehouseCommand extends MapAutoPartsWarehouseCommand

{
    private int $id;

    private ?int $quantity = null;

    private ?int $price = null;

    private ?int $quantity_sold = null;

    private ?int $Sales = null;

    private ?Counterparty $id_counterparty = null;

    private ?PartNumbersFromManufacturers $id_details = null;

    private ?PartNumbersFromManufacturers $id_manufacturer = null;

    private ?PaymentMethod $id_payment_method = null;

    private ?\DateTimeImmutable $date_receipt_auto_parts_warehouse = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function getQuantitySold(): ?int
    {
        return $this->quantity_sold;
    }

    public function getSales(): ?int
    {
        return $this->Sales;
    }

    public function getIdCounterparty(): ?Counterparty
    {
        return $this->id_counterparty;
    }

    public function getIdDetails(): ?PartNumbersFromManufacturers
    {
        return $this->id_details;
    }

    public function getIdManufacturer(): ?PartNumbersFromManufacturers
    {
        return $this->id_manufacturer;
    }

    public function getIdPaymentMethod(): ?PaymentMethod
    {
        return $this->id_payment_method;
    }

    public function getDateReceiptAutoPartsWarehouse(): ?\DateTimeImmutable
    {
        return $this->date_receipt_auto_parts_warehouse;
    }
}
