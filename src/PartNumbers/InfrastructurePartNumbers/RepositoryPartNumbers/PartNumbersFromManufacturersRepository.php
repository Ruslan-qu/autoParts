<?php

namespace App\PartNumbers\InfrastructurePartNumbers\RepositoryPartNumbers;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\PartNumbersRepositoryInterface;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartNumbersFromManufacturers;

/**
 * @extends ServiceEntityRepository<PartNumbersFromManufacturers>
 */
class PartNumbersFromManufacturersRepository extends ServiceEntityRepository implements PartNumbersRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PartNumbersFromManufacturers::class);
    }

    /**
     * @return int Возвращается число дублей 
     */
    public function numberDoubles(array $array): int
    {

        return $this->count($array);
    }

    /**
     * @return array Возвращается массив с данными об успешном сохранении
     */
    public function save(PartNumbersFromManufacturers $partNumbersFromManufacturers): int
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($partNumbersFromManufacturers);
        $entityManager->flush();

        $entityData = $entityManager->getUnitOfWork()->getOriginalEntityData($partNumbersFromManufacturers);

        $exists_part_numbers = $this->count(['id' => $entityData['id']]);
        if ($exists_part_numbers == 0) {
            $arr_data_errors = ['Error' => 'Данные в базе данных не сохранены'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $entityData['id'];
    }

    /**
     * @return array Возвращается массив с данными об успешном изменения  
     */
    public function edit(PartNumbersFromManufacturers $partNumbersFromManufacturers): int
    {
        $entityManager = $this->getEntityManager();
        $entityManager->flush();

        $entityData = $entityManager->getUnitOfWork()->getOriginalEntityData($partNumbersFromManufacturers);
        unset(
            $entityData['id_part_name_id'],
            $entityData['id_car_brand_id'],
            $entityData['id_side_id'],
            $entityData['id_body_id'],
            $entityData['id_axle_id'],
            $entityData['id_in_stock_id'],
            $entityData['id_original_number_id'],
            $entityData['id_participant_id']
        );
        $exists_part_numbers = $this->count($entityData);
        if ($exists_part_numbers == 0) {
            $arr_data_errors = ['Error' => 'Данные в базе данных не изменены'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $entityData['id'];
    }

    /**
     * @return array Возвращается массив с данными об удаление 
     */
    public function delete(PartNumbersFromManufacturers $partNumbersFromManufacturers): array
    {
        try {

            $entityManager = $this->getEntityManager();
            $entityManager->remove($partNumbersFromManufacturers);
            $entityManager->flush();
            $entityData = $entityManager->contains($partNumbersFromManufacturers);
            if ($entityData != false) {
                $arr_data_errors = ['Error' => 'Данные в базе данных не удалены'];
                $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
                throw new UnprocessableEntityHttpException($json_arr_data_errors);
            }
        } catch (\Exception $e) {
            if (!empty($e)) {
                $arr_data_errors =
                    ['Error' => 'Удаление запрещено, используется в таблице ' . '"' . 'Склад или Продажи ориг-ного номера' . '".'];
                $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
                throw new UnprocessableEntityHttpException($json_arr_data_errors);
            }
        }

        return $successfully = ['delete' => 0];
    }

    /**
     * @return PartNumbersFromManufacturers[]|NULL Возвращает массив объектов или ноль
     */
    public function findAllPartNumbers(): ?array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p, pn, c, s, b, a, i, o
            FROM App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartNumbersFromManufacturers p
            LEFT JOIN p.id_part_name pn
            LEFT JOIN p.id_car_brand c
            LEFT JOIN p.id_side s
            LEFT JOIN p.id_body b
            LEFT JOIN p.id_axle a
            LEFT JOIN p.id_in_stock i
            LEFT JOIN p.id_original_number o 
            ORDER BY p.id DESC'
        );

        return $query->getResult();
    }

    /**
     * @return PartNumbersFromManufacturers[]|NULL Возвращает массив объектов или ноль
     */
    public function findByPartNumbers(array $arr_parameters, string $part_number_where): ?array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p, pn, c, s, b, a, i, o
            FROM App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartNumbersFromManufacturers p
            LEFT JOIN p.id_part_name pn
            LEFT JOIN p.id_car_brand c
            LEFT JOIN p.id_side s
            LEFT JOIN p.id_body b
            LEFT JOIN p.id_axle a
            LEFT JOIN p.id_in_stock i
            LEFT JOIN p.id_original_number o '
                .  $part_number_where .
                'ORDER BY p.id ASC'
        )->setParameters($arr_parameters);

        return $query->getResult();
    }

    /**
     * @return PartNumbersFromManufacturers|NULL Возвращает массив объектов или ноль
     */
    public function findOneByPartNumber(string $part_number): ?PartNumbersFromManufacturers
    {
        return $this->findOneBy(['part_number' => $part_number]);
    }

    /**
     * @return ARRAY|NULL Возвращает массив объектов или ноль
     */
    public function findOneByIdPartNumber(
        int $id,
        Participant $id_participant
    ): ?array {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p, pn, c, s, b, a, i, o
            FROM App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartNumbersFromManufacturers p
            LEFT JOIN p.id_part_name pn
            LEFT JOIN p.id_car_brand c
            LEFT JOIN p.id_side s
            LEFT JOIN p.id_body b
            LEFT JOIN p.id_axle a
            LEFT JOIN p.id_in_stock i
            LEFT JOIN p.id_original_number o
            WHERE p.id = :id
            AND p.id_participant = :id_participant'
        )->setParameters(['id' => $id, 'id_participant' => $id_participant]);

        return $query->getResult();
    }

    /**
     * @return PartNumbersFromManufacturers|NULL Возвращает объект или ноль
     */
    public function findPartNumbersFromManufacturers(int $id): ?PartNumbersFromManufacturers
    {
        return $this->find($id);
    }
}
