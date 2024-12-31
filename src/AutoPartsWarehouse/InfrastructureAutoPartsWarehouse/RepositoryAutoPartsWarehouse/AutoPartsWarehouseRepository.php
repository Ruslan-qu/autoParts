<?php

namespace App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\RepositoryAutoPartsWarehouse;

use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use ContainerHt7Qm2w\EntityManagerGhost614a58f;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartNumbersFromManufacturers;
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
    public function save(AutoPartsWarehouse $autoPartsWarehouse): array
    {
        $input_errors = new InputErrorsAutoPartsWarehouse;
        $input_errors->emptyEntity($autoPartsWarehouse->getPrice());

        $entityManager = $this->getEntityManager();
        $entityManager->persist($autoPartsWarehouse);
        $entityManager->flush();

        $entityData = $entityManager->getUnitOfWork()->getOriginalEntityData($autoPartsWarehouse);

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
    public function edit(AutoPartsWarehouse $autoPartsWarehouse): array
    {
        $entityManager = $this->getEntityManager();
        $entityManager->flush();
        $entityData = $entityManager->getUnitOfWork()->getOriginalEntityData($autoPartsWarehouse);

        $exists_part_numbers = $this->count($entityData);
        if ($exists_part_numbers == 0) {
            $arr_data_errors = ['Error' => 'Данные в базе данных не изменены'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $successfully = ['edit' => $entityData['id']];
    }

    /**
     * @return array Возвращается массив с данными об удаление 
     */
    public function delete(AutoPartsWarehouse $autoPartsWarehouse): array
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($autoPartsWarehouse);
        $entityManager->flush();

        $entityData = $entityManager->contains($autoPartsWarehouse);
        if ($entityData != false) {
            $arr_data_errors = ['Error' => 'Данные в базе данных не удалены'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $successfully = ['delete' => 0];
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
                'ORDER BY a.id ASC'
        )->setParameters($arr_parameters);

        return $query->getResult();
    }

    /**
     * @return array|NULL Возвращает массив объектов или ноль
     */
    public function findAutoPartsWarehouse(int $id): ?array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT a, d, c, pm
            FROM App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse a
            LEFT JOIN a.id_details d
            LEFT JOIN a.id_counterparty c
            LEFT JOIN a.id_payment_method pm 
            WHERE a.id = :id'
        )->setParameter('id', $id);

        return $query->getResult();
    }

    /**
     * @return AutoPartsWarehouse|NULL Возвращает объект или ноль
     */
    public function findIdAutoPartsWarehouse(int $id): ?AutoPartsWarehouse
    {
        return $this->find($id);
    }

    /**
     * @return array|NULL Возвращает массив объектов или ноль
     */
    public function findCartAutoPartsWarehouse(int $id): ?array
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
            LEFT JOIN a.id_payment_method pm 
            WHERE a.id = :id'
        )->setParameter('id', $id);

        return $query->getResult();
    }

    /**
     * @return array|NULL Возвращает массив объектов или ноль
     */
    public function findByAutoPartsWarehouseDeletePartNumbers(PartNumbersFromManufacturers $find_part_numbers): ?array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT a
            FROM App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse a
            WHERE a.id_details = :id_details'
        )->setParameter('id_details', $find_part_numbers);

        return $query->getResult();
    }

    /**
     * @return array|NULL Возвращает массив объектов или ноль
     */
    public function findByAutoPartsWarehouseDeleteCounterparty(Counterparty $delete_counterparty): ?array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT a
            FROM App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse a 
            WHERE a.id_counterparty = :id_counterparty'
        )->setParameter('id_counterparty', $delete_counterparty);

        return $query->getResult();
    }
}
