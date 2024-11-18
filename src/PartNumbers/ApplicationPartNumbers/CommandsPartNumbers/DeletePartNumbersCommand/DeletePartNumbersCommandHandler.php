<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DeletePartNumbersCommand;

use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\PartNumbersRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOPartNumbersCommand\PartNumbersCommand;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\AutoPartsWarehouseRepositoryInterface;

final class DeletePartNumbersCommandHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private PartNumbersRepositoryInterface $partNumbersRepositoryInterface,
        private AutoPartsWarehouseRepositoryInterface $autoPartsWarehouseRepositoryInterface
    ) {}

    public function handler(PartNumbersCommand $partNumbersCommand): ?int
    {

        $id = $partNumbersCommand->getId();
        $this->inputErrorsPartNumbers->emptyData($id);

        $find_part_numbers = $this->partNumbersRepositoryInterface->findPartNumbersFromManufacturers($id);
        $this->inputErrorsPartNumbers->emptyEntity($find_part_numbers);

        $successfully_delete = $this->partNumbersRepositoryInterface->delete($find_part_numbers);

        return $successfully_delete['delete'];
    }
}
