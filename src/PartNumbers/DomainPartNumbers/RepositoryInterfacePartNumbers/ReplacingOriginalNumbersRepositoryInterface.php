<?php

namespace App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\ReplacingOriginalNumbers;

interface  ReplacingOriginalNumbersRepositoryInterface
{
    public function save(ReplacingOriginalNumbers $replacingOriginalNumbers): int;

    public function edit(ReplacingOriginalNumbers $replacingOriginalNumbers): int;

    public function delete(ReplacingOriginalNumbers $replacingOriginalNumbers): ?array;

    public function numberDoubles(array $array): int;

    public function findOneByReplacingOriginalNumbers(string $replacing_original_number, Participant $id_participant): ?ReplacingOriginalNumbers;

    public function findOneByOriginalNumbers(string $original_number, Participant $id_participant): ?array;

    public function findOneByIdReplacingOriginalNumbers(int $id, Participant $id_participant): ?ReplacingOriginalNumbers;

    public function countId(): ?array;

    public function findByReplacingOriginalNumbers(Participant $id_participant): ?array;

    public function findReplacingOriginalNumbers(int $id): ?ReplacingOriginalNumbers;

    public function findAllRoomsRepository(): ?array;
}
