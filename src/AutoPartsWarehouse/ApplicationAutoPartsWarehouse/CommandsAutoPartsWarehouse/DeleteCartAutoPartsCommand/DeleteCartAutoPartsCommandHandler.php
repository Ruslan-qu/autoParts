<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DeleteCartAutoPartsCommand;

use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\AutoPartsSoldRepositoryInterface;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DTOCommands\DTOAutoPartsSoldCommand\AutoPartsSoldCommand;

final class DeleteCartAutoPartsCommandHandler
{
    public function __construct(
        private AutoPartsSoldRepositoryInterface $autoPartsSoldRepositoryInterface
    ) {}

    public function handler(AutoPartsSoldCommand $autoPartsSoldCommand): ?int
    {

        $id = $autoPartsSoldCommand->getId();
        if (empty($id)) {

            $arr_data_errors = ['Error' => 'Иди некорректное'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        $find_delete_auto_parts_sold = $this->autoPartsSoldRepositoryInterface->findIdAutoPartsSold($id);
        if (empty($find_delete_auto_parts_sold)) {

            $arr_data_errors = ['Error' => 'Иди некорректное'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        $quantity_sold_auto_parts_sold = $find_delete_auto_parts_sold->getQuantitySold();
        $quantity_sold_auto_parts_warehouse = $find_delete_auto_parts_sold->getIdAutoPartsWarehouse()->getQuantitySold();
        $subtraction_quantity_sold_auto_parts_warehouse = ($quantity_sold_auto_parts_warehouse - $quantity_sold_auto_parts_sold);
        $find_delete_auto_parts_sold->getIdAutoPartsWarehouse()->setQuantitySold($subtraction_quantity_sold_auto_parts_warehouse);

        $successfully_delete = $this->autoPartsSoldRepositoryInterface->delete($find_delete_auto_parts_sold);


        return $successfully_delete['delete'];
    }
}
