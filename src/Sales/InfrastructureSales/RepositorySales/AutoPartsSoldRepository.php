<?php

namespace App\Sales\InfrastructureSales\RepositorySales;

use Doctrine\Persistence\ManagerRegistry;
use App\Sales\DomainSales\DomainModelSales\AutoPartsSold;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\Sales\DomainSales\RepositoryInterfaceSales\AutoPartsSoldRepositoryInterface;

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
     * @return array Возвращается массив с данными об успешном изменения  
     */
    public function edit(array $arr_auto_parts_sold): array
    {
        $entityManager = $this->getEntityManager();
        $entityManager->flush();

        $exists = $this->count($arr_auto_parts_sold);
        if ($exists == 0) {
            $arr_data_errors = ['Error' => 'Данные в базе данных не изменены'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $successfully = ['edit' => $arr_auto_parts_sold['id']];
    }

    /**
     * @return array Возвращается массив с данными об удаление 
     */
    public function delete(AutoPartsSold $autoPartsSold): array
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($autoPartsSold);
        $entityManager->flush();

        $entityData = $entityManager->contains($autoPartsSold);
        if ($entityData != false) {
            $arr_data_errors = ['Error' => 'Данные в базе данных не удалены'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $successfully = ['delete' => $autoPartsSold->getId()];
    }

    /**
     * @return array Возвращается массив с данными об успешном изменения  
     */
    public function sold(): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->flush();

        $exists = $entityManager->getUnitOfWork()
            ->getIdentityMap()['App\Sales\DomainSales\DomainModelSales\AutoPartsSold'];
        if (empty($exists)) {
            $arr_data_errors = ['Error' => 'Данные в базе данных не изменены'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }
    }

    /**
     * @return array|NULL Возвращает массив объектов или ноль
     */
    public function findByCartAutoPartsSold(): ?array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT s, a, d, pn, cb, sd, b, ax, c
            FROM App\Sales\DomainSales\DomainModelSales\AutoPartsSold s
            LEFT JOIN s.id_auto_parts_warehouse a
            LEFT JOIN a.id_details d
            LEFT JOIN d.id_part_name pn
            LEFT JOIN d.id_car_brand cb
            LEFT JOIN d.id_side sd
            LEFT JOIN d.id_body b
            LEFT JOIN d.id_axle ax
            LEFT JOIN a.id_counterparty c
            WHERE s.sold_status = :sold_status
            ORDER BY s.id ASC'
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
            FROM App\Sales\DomainSales\DomainModelSales\AutoPartsSold s
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

    /**
     * @return array|NULL Возвращает массив объектов или ноль
     */
    public function findOneBySalesAutoParts(int $id): ?array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT s, a, d, pn, cb, sd, b, ax, c
            FROM App\Sales\DomainSales\DomainModelSales\AutoPartsSold s
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
        )->setParameters(['sold_status' => true, 'id' => $id]);

        return $query->getResult();
    }

    /**
     * @return AutoPartsSold|NULL Возвращает объект или ноль
     */
    public function findAutoPartsSold($id): ?AutoPartsSold
    {
        return $this->find($id);
    }

    /**
     * @return array|NULL Возвращает массив объектов или ноль
     */
    public function findIdAutoPartsSold(int $id): ?AutoPartsSold
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT s, a
            FROM App\Sales\DomainSales\DomainModelSales\AutoPartsSold s
            LEFT JOIN s.id_auto_parts_warehouse a
            WHERE s.sold_status = :sold_status
            AND s.id = :id'
        )->setParameters(['sold_status' => false, 'id' => $id]);

        return $query->getResult()[0];
    }

    /**
     * @return array|NULL Возвращает массив объектов или ноль
     */
    public function findOneByAutoPartsSold(int $id): ?array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT s, a
            FROM App\Sales\DomainSales\DomainModelSales\AutoPartsSold s
            LEFT JOIN s.id_auto_parts_warehouse a
            WHERE s.sold_status = :sold_status
            AND s.id = :id'
        )->setParameters(['sold_status' => true, 'id' => $id]);

        return $query->getResult();
    }

    /**
     * @return array|NULL Возвращает массив объектов или ноль
     */
    public function findByCompletionSale(): ?array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT s, a
            FROM App\Sales\DomainSales\DomainModelSales\AutoPartsSold s
            LEFT JOIN s.id_auto_parts_warehouse a
            WHERE s.sold_status = :sold_status'
        )->setParameter('sold_status', false);

        return $query->getResult();
    }

    /**
     * @return array|NULL Возвращает массив объектов или ноль
     */
    public function findBySales($arr_parameters, $part_number_where): ?array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT s, a, d, pn, cb, sd, b, ax, o, c
            FROM App\Sales\DomainSales\DomainModelSales\AutoPartsSold s
            LEFT JOIN s.id_auto_parts_warehouse a
            LEFT JOIN a.id_details d
            LEFT JOIN d.id_part_name pn
            LEFT JOIN d.id_car_brand cb
            LEFT JOIN d.id_side sd
            LEFT JOIN d.id_body b
            LEFT JOIN d.id_axle ax
            LEFT JOIN d.id_original_number o
            LEFT JOIN a.id_counterparty c '
                .  $part_number_where .
                'ORDER BY s.id DESC'
        )->setParameters($arr_parameters);

        return $query->getResult();
    }

    /**
     * @return array|NULL Возвращает массив объектов или ноль
     */
    public function findBySalesToDate(): ?array
    {
        $date = new \DateTime();
        $format_date = $date->format('Y-m-d');

        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT s, a, d, pn, cb, sd, b, ax, o, c
            FROM App\Sales\DomainSales\DomainModelSales\AutoPartsSold s
            LEFT JOIN s.id_auto_parts_warehouse a
            LEFT JOIN a.id_details d
            LEFT JOIN d.id_part_name pn
            LEFT JOIN d.id_car_brand cb
            LEFT JOIN d.id_side sd
            LEFT JOIN d.id_body b
            LEFT JOIN d.id_axle ax
            LEFT JOIN d.id_original_number o
            LEFT JOIN a.id_counterparty c  
            WHERE s.date_sold = :date_sold
            AND s.sold_status = :sold_status
            ORDER BY s.id DESC'
        )->setParameters([
            'date_sold' => $format_date,
            'sold_status' => true
        ]);

        return $query->getResult();
    }

    /**
     * @return array|NULL Возвращает объект или ноль
     */
    public function findBySalesDeleteEditAutoPartsWarehouse($id_auto_parts_warehouse): ?array
    {
        return $this->findBy(['id_auto_parts_warehouse' => $id_auto_parts_warehouse]);
    }
}
