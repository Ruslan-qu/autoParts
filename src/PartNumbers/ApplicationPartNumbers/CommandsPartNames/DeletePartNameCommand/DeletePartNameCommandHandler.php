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
        $part_name = $partNameObjCommand->getPartName();

        $successfully_delete = $this->partNameRepositoryInterface->delete($part_name);

        return $successfully_delete['delete'];
    }
}
