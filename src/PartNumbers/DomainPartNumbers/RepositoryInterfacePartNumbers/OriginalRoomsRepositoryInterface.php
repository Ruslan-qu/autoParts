<?php

namespace App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\OriginalRooms;

interface  OriginalRoomsRepositoryInterface
{
    public function save(OriginalRooms $originalRooms): array;

    public function edit(OriginalRooms $originalRooms): array;

    public function delete(OriginalRooms $originalRooms): ?array;

    public function numberDoubles(array $array): int;

    public function findOneByOriginalRooms(string $original_number, Participant $id_participant): ?OriginalRooms;

    public function findOneByIdOriginalRoom(int $id, Participant $id_participant): ?OriginalRooms;

    public function countId(): ?array;

    public function findByOriginalRooms(Participant $id_participant): ?array;

    public function findOriginalRooms(int $id): ?OriginalRooms;
}
