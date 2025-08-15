<?php

namespace App\PartNumbers\InfrastructurePartNumbers\RepositoryPartNumbers;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\ReplacingOriginalNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\ReplacingOriginalNumbersRepositoryInterface;

/**
 * @extends ServiceEntityRepository<ReplacingOriginalNumber>
 */
class ReplacingOriginalNumbersRepository extends ServiceEntityRepository implements ReplacingOriginalNumbersRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReplacingOriginalNumbers::class);
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
    public function save(ReplacingOriginalNumbers $replacing_original_numbers): int
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($replacing_original_numbers);
        $entityManager->flush();

        $entityData = $entityManager->getUnitOfWork()->getOriginalEntityData($replacing_original_numbers);

        $exists = $this->count(['id' => $entityData['id']]);
        if ($exists == 0) {
            $arr_data_errors = ['Error' => 'Данные в базе данных не сохранены'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }
        return $entityData['id'];
    }

    /**
     * @return int Возвращается id при успешном изменения  
     */
    public function edit(ReplacingOriginalNumbers $replacing_original_numbers): int
    {
        $entityManager = $this->getEntityManager();
        $entityManager->flush();
        $entityData = $entityManager->getUnitOfWork()->getOriginalEntityData($replacing_original_numbers);
        unset($entityData['id_original_number_id']);
        unset($entityData['id_participant_id']);

        $exists = $this->count($entityData);
        if ($exists == 0) {
            $arr_data_errors = ['Error' => 'Данные в базе данных не изменены'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $entityData['id'];
    }

    /**
     * @return array Возвращается массив с данными об удаление 
     */
    public function delete(ReplacingOriginalNumbers $replacing_original_numbers): ?array
    {
        try {

            $entityManager = $this->getEntityManager();
            $entityManager->remove($replacing_original_numbers);
            $entityManager->flush();
            $entityData = $entityManager->contains($replacing_original_numbers);
            if ($entityData != false) {
                $arr_data_errors = ['Error' => 'Данные в базе данных не удалены'];
                $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
                throw new UnprocessableEntityHttpException($json_arr_data_errors);
            }
        } catch (\Exception $e) {
            if (!empty($e)) {
                $arr_data_errors = ['Error' => 'Удаление запрещено, используется в таблице ' . '"' . 'Детали или Замена ориг-ного номера' . '".'];
                $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
                throw new UnprocessableEntityHttpException($json_arr_data_errors);
            }
        }

        return $successfully = ['delete' => 0];
    }

    /**
     * @return ReplacingOriginalNumbers|NULL Возвращает массив объектов или ноль
     */
    public function findOneByReplacingOriginalNumbers(
        string $replacing_original_number,
        Participant $id_participant
    ): ?ReplacingOriginalNumbers {
        return $this->findOneBy(['replacing_original_number' => $replacing_original_number, 'id_participant' => $id_participant]);
    }

    /**
     * @return ARRAY|NULL Возвращает массив объектов или ноль
     */
    public function findOneByOriginalNumbers(
        string $original_number,
        Participant $id_participant
    ): ?array {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT r, o
            FROM App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\ReplacingOriginalNumbers r
            LEFT JOIN r.id_original_number o
            WHERE o.original_number = :original_number
            AND r.id_participant = :id_participant
            ORDER BY r.id ASC'
        )->setParameters(['original_number' => $original_number, 'id_participant' => $id_participant]);

        return $query->getResult();
    }

    /**
     * @return ReplacingOriginalNumbers|NULL Возвращает объект или ноль
     */
    public function findOneByIdReplacingOriginalNumbers(int $id, Participant $id_participant): ?ReplacingOriginalNumbers
    {
        return $this->findOneBy(['id' => $id, 'id_participant' => $id_participant]);
    }

    /**
     * @return ARRAY|NULL Возвращает массив количество id или ноль
     */
    public function countId(): ?array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT COUNT(o.id)
            FROM App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\ReplacingOriginalNumbers o'
        );

        return $query->getResult();
    }

    /**
     * @return ARRAY|NULL Возвращает массив объектов или ноль
     */
    public function findByReplacingOriginalNumbers(Participant $id_participant): ?array
    {

        return $this->findBy(['id_participant' => $id_participant]);
    }

    /**
     * @return ReplacingOriginalNumbers|NULL Возвращает объект или ноль
     */
    public function findReplacingOriginalNumbers(int $id): ?ReplacingOriginalNumbers
    {
        return $this->find($id);
    }

    /**
     * @return ARRAY|NULL Возвращает объект или ноль
     */
    public function findAllRoomsRepository(): ?array
    {
        return $this->findAll();
    }
}
