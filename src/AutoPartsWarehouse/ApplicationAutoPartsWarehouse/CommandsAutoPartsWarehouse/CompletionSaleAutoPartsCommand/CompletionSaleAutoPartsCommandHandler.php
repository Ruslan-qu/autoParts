<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\SaveAutoPartsWarehouseCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\AutoPartsSoldRepositoryInterface;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DTOCommands\DTOAutoPartsWarehouseCommand\AutoPartsWarehouseCommand;

final class CompletionSaleAutoPartsCommandHandler
{

    public function __construct(
        private AutoPartsSoldRepositoryInterface $autoPartsSoldRepositoryInterface,
        private AutoPartsWarehouse $autoPartsWarehouse
    ) {}

    public function handler(): ?int
    {

        $find_cart_auto_parts_sold = $this->autoPartsSoldRepositoryInterface->findByCartAutoPartsSold();

        $this->autoPartsWarehouse->setQuantity($quantity);
        $this->autoPartsWarehouse->setPrice($price);
        $this->autoPartsWarehouse->setIdCounterparty($counterparty);
        $this->autoPartsWarehouse->setIdDetails($part_number);
        $this->autoPartsWarehouse->setDateReceiptAutoPartsWarehouse($date_receipt_auto_parts_warehouse);
        $this->autoPartsWarehouse->setIdPaymentMethod($payment_method);

        $successfully_save = $this->autoPartsWarehouseRepositoryInterface->save($this->autoPartsWarehouse);

        $id = $successfully_save['save'];
        return $id;
    }
}
