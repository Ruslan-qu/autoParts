<?php

namespace App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;

interface  CounterpartyRepositoryInterface
{
    public function save(Counterparty $entity_counterparty): int;

    public function edit(Counterparty $edit_counterparty): int;

    public function delete(Counterparty $entity_counterparty): int;

    public function numberDoubles(array $array): int;

    public function findAllCounterparty(): ?array;

    public function findByCounterparty(Participant $id_participant): ?array;

    public function findOneByCounterparty(string $name_counterparty, Participant $id_participant): ?Counterparty;

    public function findOneByEmailCounterparty(string $mail_counterparty, Participant $id_participant): ?Counterparty;

    public function findCounterparty($id): ?Counterparty;

    public function findOneByIdCounterparty(int $id, Participant $id_participant): ?Counterparty;
}
