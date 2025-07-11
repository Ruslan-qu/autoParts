<?php

namespace App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Availability;

interface  AvailabilityRepositoryInterface
{
    public function save(Availability $availability): int;

    public function edit(Availability $availability): array;

    public function delete(Availability $availability): ?array;

    public function numberDoubles(array $array): int;

    public function findOneByAvailability(string $in_stock, Participant $id_participant): ?Availability;

    public function findOneByIdAvailability(int $id, Participant $id_participant): ?Availability;

    public function countId(): ?array;

    public function findByAvailability(Participant $id_participant): ?array;

    public function findAvailability(int $id): ?Availability;
}
