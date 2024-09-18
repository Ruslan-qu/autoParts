<?php

namespace App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers;

use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\OriginalRooms;

interface  OriginalRoomsRepositoryInterface
{
    public function save(OriginalRooms $originalRooms): array;

    public function edit(OriginalRooms $partNumbersFromManufacturers): array;

    public function delete(OriginalRooms $partNumbersFromManufacturers): array;

    public function numberDoubles(array $array): int;

    public function findAllOriginalRooms(): ?array;

    public function findOneByOriginalRooms(string $original_number): ?OriginalRooms;

    public function findOriginalRooms(int $id): ?OriginalRooms;
}
