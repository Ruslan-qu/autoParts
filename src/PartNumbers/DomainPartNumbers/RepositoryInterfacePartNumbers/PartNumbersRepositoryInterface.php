<?php

namespace App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers;

use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartNumbersFromManufacturers;

interface  PartNumbersRepositoryInterface
{
    public function save(PartNumbersFromManufacturers $partNumbersFromManufacturers): array;

    public function edit(PartNumbersFromManufacturers $partNumbersFromManufacturers): array;

    public function delete(PartNumbersFromManufacturers $partNumbersFromManufacturers): array;

    public function numberDoubles(array $array): int;

    public function findAllPartNumbersFromManufacturers(): ?array;

    public function findByPartNumbers(array $parameters, string $where): ?array;

    public function findPartNumbersFromManufacturers(int $id): ?PartNumbersFromManufacturers;
}
