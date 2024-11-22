<?php

namespace App\Sales\ApplicationSales\CommandsSales\DeleteSalesAutoPartsCommand;

use App\Sales\ApplicationSales\ErrorsSales\InputErrorsSales;
use App\Sales\DomainSales\RepositoryInterfaceSales\AutoPartsSoldRepositoryInterface;
use App\Sales\ApplicationSales\CommandsSales\DTOAutoPartsSoldCommand\AutoPartsSoldCommand;

final class DeleteSalesAutoPartsCommandHandler
{
    public function __construct(
        private InputErrorsSales $inputErrorsSales,
        private AutoPartsSoldRepositoryInterface $autoPartsSoldRepositoryInterface
    ) {}

    public function handler(AutoPartsSoldCommand $autoPartsSoldCommand): ?int
    {

        $id = $autoPartsSoldCommand->getId();
        $this->inputErrorsSales->emptyData($id);

        $find_delete_auto_parts_sold = $this->autoPartsSoldRepositoryInterface->findOneByAutoPartsSold($id)[0];
        $this->inputErrorsSales->emptyEntity($find_delete_auto_parts_sold);

        $quantity_sold_auto_parts_sold = $find_delete_auto_parts_sold->getQuantitySold();
        $quantity_sold_auto_parts_warehouse = $find_delete_auto_parts_sold->getIdAutoPartsWarehouse()->getQuantitySold();
        $subtraction_quantity_sold_auto_parts_warehouse = ($quantity_sold_auto_parts_warehouse - $quantity_sold_auto_parts_sold);
        $find_delete_auto_parts_sold->getIdAutoPartsWarehouse()->setQuantitySold($subtraction_quantity_sold_auto_parts_warehouse);
        if (
            $find_delete_auto_parts_sold->getIdAutoPartsWarehouse()->getSales() == 1
            && $find_delete_auto_parts_sold->getIdAutoPartsWarehouse()->getQuantity() != $subtraction_quantity_sold_auto_parts_warehouse
        ) {
            $find_delete_auto_parts_sold->getIdAutoPartsWarehouse()->setSales(0);
        }
        $successfully_delete = $this->autoPartsSoldRepositoryInterface->delete($find_delete_auto_parts_sold);

        return $successfully_delete['delete'];
    }
}
