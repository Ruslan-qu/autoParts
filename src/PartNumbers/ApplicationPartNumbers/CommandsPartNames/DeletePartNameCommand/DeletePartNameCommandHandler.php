<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsPartNames\DeletePartNameCommand;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\PartNumbersRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNames\DTOCommands\DTOPartNameCommand\PartNameCommand;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\AutoPartsWarehouseRepositoryInterface;

final class DeletePartNameCommandHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private PartNumbersRepositoryInterface $partNumbersRepositoryInterface,
        private AutoPartsWarehouseRepositoryInterface $autoPartsWarehouseRepositoryInterface
    ) {}

    public function handler(PartNameCommand $partNameCommand): ?int
    {

        $id = $partNameCommand->getId();
        $this->inputErrorsPartNumbers->emptyData($id);

        $find_part_numbers = $this->partNumbersRepositoryInterface->findPartNumbersFromManufacturers($id);
        $this->inputErrorsPartNumbers->emptyEntity($find_part_numbers);

        $successfully_delete = $this->partNumbersRepositoryInterface->delete($find_part_numbers);

        return $successfully_delete['delete'];
    }
}
