<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOAutoPartsWarehouseDeleteQuery;


use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartNumbersFromManufacturers;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\PaymentMethod;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOAutoPartsWarehouseDeleteQuery\MapAutoPartsWarehouseDeleteQuery;

final class AutoPartsWarehouseDeleteQuery extends MapAutoPartsWarehouseDeleteQuery

{
    protected ?int $id = null;

    protected ?int $quantity = null;

    protected ?int $price = null;

    protected ?int $quantity_sold = null;

    protected ?int $sales = null;

    protected ?Counterparty $id_counterparty = null;

    protected ?PartNumbersFromManufacturers $id_details = null;

    protected ?PaymentMethod $id_payment_method = null;

    protected ?\DateTimeImmutable $date_receipt_auto_parts_warehouse = null;

    public function getId(): ?int
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
        return $this->sales;
    }

    public function getIdCounterparty(): ?Counterparty
    {
        return $this->id_counterparty;
    }

    public function getIdDetails(): ?PartNumbersFromManufacturers
    {
        return $this->id_details;
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
