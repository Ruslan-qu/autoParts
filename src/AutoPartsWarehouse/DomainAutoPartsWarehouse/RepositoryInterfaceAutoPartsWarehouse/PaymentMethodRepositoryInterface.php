<?php

namespace App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\PaymentMethod;


interface PaymentMethodRepositoryInterface
{
    public function save(PaymentMethod $payment_method): int;

    public function edit(PaymentMethod $payment_method): int;

    public function delete(PaymentMethod $payment_method): int;

    public function numberDoubles(array $array): int;

    public function findAllPaymentMethod(): ?array;

    public function findByPaymentMethod(Participant $id_participant): ?array;

    // public function findOneByPaymentMethod(string $method, Participant $id_participant): ?PaymentMethod;

    public function findPaymentMethod($id): ?PaymentMethod;

    public function findOneByIdPaymentMethod(int $id, Participant $id_participant): ?PaymentMethod;

    public function findOneByPaymentMethod(int|null $id): ?PaymentMethod;
}
