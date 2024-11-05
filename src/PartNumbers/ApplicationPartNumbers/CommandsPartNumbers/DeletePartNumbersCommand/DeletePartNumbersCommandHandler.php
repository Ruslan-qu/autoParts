<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DeletePartNumbersCommand;

use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\PartNumbersRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOPartNumbersCommand\PartNumbersCommand;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\AutoPartsWarehouseRepositoryInterface;

final class DeletePartNumbersCommandHandler
{

    public function __construct(
        private PartNumbersRepositoryInterface $partNumbersRepositoryInterface,
        private AutoPartsWarehouseRepositoryInterface $autoPartsWarehouseRepositoryInterface
    ) {}

    public function handler(PartNumbersCommand $partNumbersCommand): ?array
    {

        $id = $partNumbersCommand->getId();
        if (empty($id)) {

            $arr_data_errors = ['Error' => 'Иди некорректное'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        $find_part_numbers = $this->partNumbersRepositoryInterface->findPartNumbersFromManufacturers($id);

        if (empty($find_part_numbers)) {

            $arr_data_errors = ['Error' => 'Иди некорректное'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        $delete_part_numbers_warehouse = $this->autoPartsWarehouseRepositoryInterface
            ->findByAutoPartsWarehouseDeletePartNumbers($find_part_numbers);

        if (!empty($delete_part_numbers_warehouse)) {

            $arr_data_errors = ['Error' => 'Данная деталь есть на складе, перед удалением, измените номер деталь на складе'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        $successfully_delete = $this->partNumbersRepositoryInterface->delete($find_part_numbers);

        return $successfully_delete;
    }
}
