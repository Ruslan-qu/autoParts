<?php

namespace App\PartNumbers\InfrastructurePartNumbers\RepositoryPartNumbers;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartName;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\PartNameRepositoryInterface;

/**
 * @extends ServiceEntityRepository<PartName>
 */
class PartNameRepository extends ServiceEntityRepository implements PartNameRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PartName::class);
    }

    /**
     * @return PartName|NULL Возвращает массив объектов или ноль
     */
    public function findOneByPartName(string $part_name): ?PartName
    {
        return $this->findOneBy(['part_name' => $part_name]);
    }
}
