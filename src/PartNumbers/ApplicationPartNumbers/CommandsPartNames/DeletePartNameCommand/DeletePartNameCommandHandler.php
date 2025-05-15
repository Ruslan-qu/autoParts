<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsPartNames\DeletePartNameCommand;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\PartNameRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNames\DTOCommands\DTOPartNameObjCommand\PartNameObjCommand;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\AutoPartsWarehouseRepositoryInterface;

final class DeletePartNameCommandHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private PartNameRepositoryInterface $partNameRepositoryInterface,
        private AutoPartsWarehouseRepositoryInterface $autoPartsWarehouseRepositoryInterface
    ) {}

    public function handler(PartNameObjCommand $partNameObjCommand): ?int
    {


        $successfully_delete = $this->partNameRepositoryInterface->delete($find_part_numbers);

        return $successfully_delete['delete'];
    }
}
