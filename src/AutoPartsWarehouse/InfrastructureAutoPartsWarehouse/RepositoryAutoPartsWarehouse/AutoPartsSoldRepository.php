<?php

namespace App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\RepositoryAutoPartsWarehouse;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsSold;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\AutoPartsSoldRepositoryInterface;

/**
 * @extends ServiceEntityRepository<AutoPartsSold>
 */
class AutoPartsSoldRepository extends ServiceEntityRepository implements AutoPartsSoldRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AutoPartsSold::class);
    }

    /**
     * @return array Возвращается массив с данными об успешном сохранении
     */
    public function save(AutoPartsSold $autoPartsSold): array
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($autoPartsSold);
        $entityManager->flush();

        $entityData = $entityManager->getUnitOfWork()->getOriginalEntityData($autoPartsSold);

        $exists = $this->count($entityData);
        if ($exists == 0) {
            $arr_data_errors = ['Error' => 'Данные в базе данных не сохранены'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }
        return $successfully = ['save' => $entityData['id']];
    }



    /**
     * @return array|NULL Возвращает массив объектов или ноль
     */
    public function findByCartAutoPartsSold(): ?array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT s, a, d, pn, cb, sd, b, ax, c
            FROM App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsSold s
            LEFT JOIN s.id_auto_parts_warehouse a
            LEFT JOIN a.id_details d
            LEFT JOIN d.id_part_name pn
            LEFT JOIN d.id_car_brand cb
            LEFT JOIN d.id_side sd
            LEFT JOIN d.id_body b
            LEFT JOIN d.id_axle ax
            LEFT JOIN a.id_counterparty c
            WHERE s.sold_status = :sold_status'
        )->setParameter('sold_status', false);

        return $query->getResult();
    }

    /**
     * @return array|NULL Возвращает массив объектов или ноль
     */
    public function findСartAutoPartsWarehouseSold(int $id): ?array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT s, a, d, pn, cb, sd, b, ax, c
            FROM App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsSold s
            LEFT JOIN s.id_auto_parts_warehouse a
            LEFT JOIN a.id_details d
            LEFT JOIN d.id_part_name pn
            LEFT JOIN d.id_car_brand cb
            LEFT JOIN d.id_side sd
            LEFT JOIN d.id_body b
            LEFT JOIN d.id_axle ax
            LEFT JOIN a.id_counterparty c
            WHERE s.sold_status = :sold_status
            AND s.id = :id'
        )->setParameters(['sold_status' => false, 'id' => $id]);

        return $query->getResult();
    }
}
