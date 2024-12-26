<?php

namespace App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\RepositoryAutoPartsWarehouse;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\PaymentMethod;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\PaymentMethodRepositoryInterface;

/**
 * @extends ServiceEntityRepository<PaymentMethod>
 */
class PaymentMethodRepository extends ServiceEntityRepository implements PaymentMethodRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentMethod::class);
    }

    /**
     * @return PaymentMethod|NULL Возвращает объектов или ноль
     */
    public function findOneByPaymentMethod(int|null $id): ?PaymentMethod
    {
        return $this->findOneBy(['id' => $id]);
    }
}
