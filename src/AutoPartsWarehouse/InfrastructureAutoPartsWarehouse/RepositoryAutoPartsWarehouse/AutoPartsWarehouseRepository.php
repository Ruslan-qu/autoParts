<?php

namespace App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\RepositoryAutoPartsWarehouse;

use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\AutoPartsWarehouseRepositoryInterface;

/**
 * @extends ServiceEntityRepository<AutoPartsWarehouse>
 */
class AutoPartsWarehouseRepository extends ServiceEntityRepository implements AutoPartsWarehouseRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AutoPartsWarehouse::class);
    }

    /**
     * @return EntityManager Возвращается объект EntityManager
     */
    public function persistData(AutoPartsWarehouse $autoPartsWarehouse)
    {
        $input_errors = new InputErrorsAutoPartsWarehouse;
        $input_errors->emptyEntity($autoPartsWarehouse->getPrice());

        $entityManager = $this->getEntityManager();
        $entityManager->persist($autoPartsWarehouse);

        return $entityManager;
    }

    /**
     * @return array Возвращается массив с данными об успешном сохранении
     */
    public function flushData($entityManager, $autoPartsWarehouse, $count_key): array
    {
        $input_errors = new InputErrorsAutoPartsWarehouse;

        $entityManager->flush();

        $entityData = $entityManager
            ->getUnitOfWork()
            ->getIdentityMap()['App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse'];
        $input_errors->countArr($entityData, $count_key);

        return $successfully = ['save' => 'saved'];
    }

    /**
     * @return array Возвращается массив с данными об успешном сохранении
     */
    public function save(AutoPartsWarehouse $autoPartsWarehouse): int
    {
        $input_errors = new InputErrorsAutoPartsWarehouse;
        $input_errors->emptyEntity($autoPartsWarehouse->getPrice());

        $entityManager = $this->getEntityManager();
        $entityManager->persist($autoPartsWarehouse);
        $entityManager->flush();

        $entityData = $entityManager->getUnitOfWork()->getOriginalEntityData($autoPartsWarehouse);

        $exists = $this->count(['id' => $entityData['id']]);
        if ($exists == 0) {
            $arr_data_errors = ['Error' => 'Данные в базе данных не сохранены'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }
        return $entityData['id'];
    }

    /**
     * @return array Возвращается массив с данными об успешном изменения  
     */
    public function edit(AutoPartsWarehouse $autoPartsWarehouse): array
    {
        $entityManager = $this->getEntityManager();
        $entityManager->flush();
        $entityData = $entityManager->getUnitOfWork()->getOriginalEntityData($autoPartsWarehouse);

        $exists = $this->count($entityData);
        if ($exists == 0) {
            $arr_data_errors = ['Error' => 'Данные в базе данных не изменены'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $successfully = ['edit' => $autoPartsWarehouse->getId()];
    }

    /**
     * @return array Возвращается массив с данными об удаление 
     */
    public function delete(AutoPartsWarehouse $autoPartsWarehouse): array
    {
        try {

            $entityManager = $this->getEntityManager();
            $entityManager->remove($autoPartsWarehouse);
            $entityManager->flush();

            $entityData = $entityManager->contains($autoPartsWarehouse);
            if ($entityData != false) {
                $arr_data_errors = ['Error' => 'Данные в базе данных не удалены'];
                $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
                throw new UnprocessableEntityHttpException($json_arr_data_errors);
            }
        } catch (\Exception $e) {
            if (!empty($e)) {
                $arr_data_errors = ['Error' => 'Удаление запрещено, используется в таблице ' . '"' . 'Продажи' . '".'];
                $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
                throw new UnprocessableEntityHttpException($json_arr_data_errors);
            }
        }

        return $successfully = ['delete' => 0];
    }

    /**
     * @return AutoPartsWarehouse[]|NULL Возвращает массив объектов поставщиков или ноль
     */
    public function findAllAutoPartsWarehouse(): ?array
    {
        return $this->findAll();
    }

    /**
     * @return AutoPartsWarehouse[]|NULL Возвращает массив объектов или ноль
     */
    public function findByAutoPartsWarehouse(array $arr_parameters, string $part_number_where): ?array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT a, d, pn, cb, s, b, ax, o, c, pm
            FROM App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse a
            LEFT JOIN a.id_details d
            LEFT JOIN d.id_part_name pn
            LEFT JOIN d.id_car_brand cb
            LEFT JOIN d.id_side s
            LEFT JOIN d.id_body b
            LEFT JOIN d.id_axle ax
            LEFT JOIN d.id_original_number o
            LEFT JOIN a.id_counterparty c
            LEFT JOIN a.id_payment_method pm '
                .  $part_number_where .
                'ORDER BY a.date_receipt_auto_parts_warehouse ASC'
        )->setParameters($arr_parameters);

        return $query->getResult();
    }

    /**
     * @return array|NULL Возвращает массив объектов или ноль
     */
    public function findOneByJoinAutoPartsWarehouse(int $id, Participant $id_participant): ?array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT a, d, c, pm
            FROM App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse a
            LEFT JOIN a.id_details d
            LEFT JOIN a.id_counterparty c
            LEFT JOIN a.id_payment_method pm 
            WHERE a.id = :id
            AND a.id_participant = :id_participant'
        )->setParameters(['id' => $id,  'id_participant' => $id_participant]);

        return $query->getResult();
    }

    /**
     * @return AutoPartsWarehouse|NULL Возвращает объект или ноль
     */
    public function findOneByAutoPartsWarehouse(int $id, Participant $id_participant): ?AutoPartsWarehouse
    {
        return $this->findOneBy(['id' => $id, 'id_participant' => $id_participant]);
    }

    /**
     * @return array|NULL Возвращает массив объектов или ноль
     */
    public function findOneByCartAutoPartsWarehouse(int $id, Participant $id_participant): ?array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT a, d, pn, cb, s, b, ax, o, c, pm, i
            FROM App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse a
            LEFT JOIN a.id_details d
            LEFT JOIN d.id_part_name pn
            LEFT JOIN d.id_car_brand cb
            LEFT JOIN d.id_side s
            LEFT JOIN d.id_body b
            LEFT JOIN d.id_axle ax
            LEFT JOIN d.id_original_number o
            LEFT JOIN a.id_counterparty c
            LEFT JOIN a.id_payment_method pm 
            LEFT JOIN a.id_participant i 
            WHERE a.id = :id
            AND a.id_participant = :id_participant'
        )->setParameters(['id' => $id,  'id_participant' => $id_participant]);

        return $query->getResult();
    }

    /**
     * @return int|NULL число или ноль
     */
    public function emptyDateAutoPartsWarehouse(DateTimeImmutable $contentHeadersDate): ?int
    {
        return $this->count(['date_receipt_auto_parts_warehouse' => $contentHeadersDate]);
    }

    /**
     * @return array|NULL Возвращает массив объектов или ноль
     */
    public function findByShipmentToDate(Participant $id_participant): ?array
    {
        $date = new \DateTime();
        $format_date = $date->format('Y-m-d');

        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT a, d, pn, cb, s, b, ax, o, c, pm
            FROM App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse a
            LEFT JOIN a.id_details d
            LEFT JOIN d.id_part_name pn
            LEFT JOIN d.id_car_brand cb
            LEFT JOIN d.id_side s
            LEFT JOIN d.id_body b
            LEFT JOIN d.id_axle ax
            LEFT JOIN d.id_original_number o
            LEFT JOIN a.id_counterparty c
            LEFT JOIN a.id_payment_method pm 
            WHERE a.date_receipt_auto_parts_warehouse >= :date_receipt_auto_parts_warehouse
            AND a.sales = :sales
            AND a.id_participant = :id_participant
            ORDER BY s.id ASC'
        )->setParameters([
            'date_receipt_auto_parts_warehouse' => $format_date,
            'sales' => 0,
            'id_participant' => $id_participant
        ]);

        return $query->getResult();
    }
}
