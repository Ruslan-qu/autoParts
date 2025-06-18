<?php

namespace App\PartNumbers\InfrastructurePartNumbers\RepositoryPartNumbers;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
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
     * @return int Возвращается число дублей 
     */
    public function numberDoubles(array $array): int
    {

        return $this->count($array);
    }

    /**
     * @return array Возвращается массив с данными об успешном сохранении
     */
    public function save(PartName $partName): int
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($partName);
        $entityManager->flush();

        $entityData = $entityManager->getUnitOfWork()->getOriginalEntityData($partName);

        $exists_part_name = $this->count($entityData);
        if ($exists_part_name == 0) {
            $arr_data_errors = ['Error' => 'Данные в базе данных не сохранены'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $entityData['id'];
    }

    /**
     * @return array Возвращается массив с данными об удаление 
     */
    public function delete(PartName $partName): array
    {
        try {

            $entityManager = $this->getEntityManager();
            $entityManager->remove($partName);
            $entityManager->flush();
            $entityData = $entityManager->contains($partName);
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
     * @return PartName|NULL Возвращает объект или ноль
     */
    public function findOneByPartName(string $part_name, Participant $id_participant): ?PartName
    {
        return $this->findOneBy(['part_name' => $part_name, 'id_participant' => $id_participant]);
    }

    /**
     * @return PartName|NULL Возвращает или ноль
     */
    public function findOneByIdPartName(int $id, Participant $id_participant): ?PartName
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
            'SELECT COUNT(p.id)
            FROM App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartName p'
        );

        return $query->getResult();
    }

    /**
     * @return PartName|NULL Возвращает массив объектов или ноль
     */
    public function findByPartName(Participant $id_participant): ?array
    {

        return $this->findBy(['id_participant' => $id_participant]);
    }

    /**
     * @return PartName|NULL Возвращает массив объектов или ноль
     */
    public function findPartName(int $id): ?PartName
    {

        return $this->find($id);
    }
}
