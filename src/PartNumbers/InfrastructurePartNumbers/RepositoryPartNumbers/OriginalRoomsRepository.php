<?php

namespace App\PartNumbers\InfrastructurePartNumbers\RepositoryPartNumbers;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\OriginalRooms;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\OriginalRoomsRepositoryInterface;

/**
 * @extends ServiceEntityRepository<OriginalRooms>
 */
class OriginalRoomsRepository extends ServiceEntityRepository implements OriginalRoomsRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OriginalRooms::class);
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
    public function save(OriginalRooms $originalRooms): array
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($originalRooms);
        $entityManager->flush();

        $entityData = $entityManager->getUnitOfWork()->getOriginalEntityData($originalRooms);

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
    public function edit(OriginalRooms $originalRooms): array
    {
        $entityManager = $this->getEntityManager();
        $entityManager->flush();
        $entityData = $entityManager->getUnitOfWork()->getOriginalEntityData($originalRooms);

        $exists = $this->count($entityData);
        if ($exists == 0) {
            $arr_data_errors = ['Error' => 'Данные в базе данных не изменены'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $successfully = ['edit' => $entityData['id']];
    }

    /**
     * @return array Возвращается массив с данными об удаление 
     */
    public function delete(OriginalRooms $originalRooms): ?array
    {
        try {

            $entityManager = $this->getEntityManager();
            $entityManager->remove($originalRooms);
            $entityManager->flush();
            $entityData = $entityManager->contains($originalRooms);
            if ($entityData != false) {
                $arr_data_errors = ['Error' => 'Данные в базе данных не удалены'];
                $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
                throw new UnprocessableEntityHttpException($json_arr_data_errors);
            }
        } catch (\Exception $e) {
            if (!empty($e)) {
                $arr_data_errors = ['Error' => 'Удаление запрещено, используется в таблице ' . '"' . 'Детали' . '".'];
                $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
                throw new UnprocessableEntityHttpException($json_arr_data_errors);
            }
        }

        return $successfully = ['delete' => 0];
    }

    /**
     * @return OriginalRooms|NULL Возвращает массив объектов или ноль
     */
    public function findOneByOriginalRooms(string $original_number, Participant $id_participant): ?OriginalRooms
    {
        return $this->findOneBy(['original_number' => $original_number, 'id_participant' => $id_participant]);
    }

    /**
     * @return OriginalRooms|NULL Возвращает объект или ноль
     */
    public function findOneByIdOriginalRooms(int $id, Participant $id_participant): ?OriginalRooms
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
            FROM App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\OriginalRooms o'
        );

        return $query->getResult();
    }

    /**
     * @return ARRAY|NULL Возвращает массив объектов или ноль
     */
    public function findByOriginalRooms(Participant $id_participant): ?array
    {

        return $this->findBy(['id_participant' => $id_participant]);
    }

    /**
     * @return OriginalRooms|NULL Возвращает объект или ноль
     */
    public function findOriginalRooms(int $id): ?OriginalRooms
    {
        return $this->find($id);
    }
}
