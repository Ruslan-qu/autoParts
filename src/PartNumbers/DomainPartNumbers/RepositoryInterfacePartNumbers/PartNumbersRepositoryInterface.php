<?php

namespace App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartNumbersFromManufacturers;

interface  PartNumbersRepositoryInterface
{
    public function save(PartNumbersFromManufacturers $partNumbersFromManufacturers): int;

    public function edit(PartNumbersFromManufacturers $part_nupartNumbersFromManufacturersmber): int;

    public function delete(PartNumbersFromManufacturers $partNumbersFromManufacturers): ?array;

    public function numberDoubles(array $array): int;

    public function findByPartNumbers(array $parameters, string $where): ?array;

    public function findOneByPartNumber(string $part_number, Participant $id_participant): ?PartNumbersFromManufacturers;

    public function findOneByIdPartNumber(int $id, Participant $id_participant): ?array;

    public function findAllPartNumbers(): ?array;

    public function findPartNumbersFromManufacturers(int $id): ?PartNumbersFromManufacturers;
}
