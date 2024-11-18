<?php

namespace App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers;

use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartNumbersFromManufacturers;

interface  PartNumbersRepositoryInterface
{
    public function save(PartNumbersFromManufacturers $partNumbersFromManufacturers): array;

    public function edit(array $arr_edit_part_number): array;

    public function delete(PartNumbersFromManufacturers $partNumbersFromManufacturers): ?array;

    public function numberDoubles(array $array): int;

    public function findByPartNumbers(array $parameters, string $where): ?array;

    public function findOneByPartNumber(string $part_number): ?PartNumbersFromManufacturers;

    public function findAllPartNumbers(): ?array;

    public function findPartNumbersFromManufacturers(int $id): ?PartNumbersFromManufacturers;
}
