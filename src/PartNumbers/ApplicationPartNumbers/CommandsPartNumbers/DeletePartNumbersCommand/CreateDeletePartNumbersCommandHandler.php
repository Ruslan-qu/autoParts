<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DeletePartNumbersCommand;

use App\PartNumbers\InfrastructurePartNumbers\RepositoryPartNumbers\PartNumbersFromManufacturersRepository;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOPartNumbersCommand\CreatePartNumbersCommand;

final class CreateDeletePartNumbersCommandHandler
{
    private $part_numbers_from_manufacturers_repository;

    public function __construct(
        PartNumbersFromManufacturersRepository $partNumbersFromManufacturersRepository
    ) {
        $this->part_numbers_from_manufacturers_repository = $partNumbersFromManufacturersRepository;
    }

    public function handler(CreatePartNumbersCommand $createPartNumbersCommand): ?array
    {

        $id = $createPartNumbersCommand->getId();
        if (empty($id)) {

            return null;
        }

        $find_part_numbers = $this->part_numbers_from_manufacturers_repository->findPartNumbersFromManufacturers($id);
        if (empty($find_part_numbers)) {

            return null;
        }

        $successfully_delete = $this->part_numbers_from_manufacturers_repository->delete($find_part_numbers);

        $successfully['successfully'] = $successfully_delete;
        return $successfully;
    }
}
