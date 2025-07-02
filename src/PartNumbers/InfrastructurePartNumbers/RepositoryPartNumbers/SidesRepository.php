<?php

namespace App\PartNumbers\InfrastructurePartNumbers\RepositoryPartNumbers;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Sides;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\SidesRepositoryInterface;

/**
 * @extends ServiceEntityRepository<Sides>
 */
class SidesRepository extends ServiceEntityRepository implements SidesRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sides::class);
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
    public function save(Sides $sides): int
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($sides);
        $entityManager->flush();

        $entityData = $entityManager->getUnitOfWork()->getOriginalEntityData($sides);

        $exists_sides = $this->count($entityData);
        if ($exists_sides == 0) {
            $arr_data_errors = ['Error' => 'Данные в базе данных не сохранены'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $entityData['id'];
    }

    /**
     * @return array Возвращается массив с данными об успешном изменения  
     */
    public function edit(Sides $sides): array
    {
        $entityManager = $this->getEntityManager();
        $entityManager->flush();

        $exists_sides = $this->count(['side' => $sides->getSide()]);
        if ($exists_sides == 0) {
            $arr_data_errors = ['Error' => 'Данные в базе данных не изменены'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $successfully = ['edit' => $sides->getId()];
    }

    /**
     * @return array Возвращается массив с данными об удаление 
     */
    public function delete(Sides $sides): array
    {
        try {

            $entityManager = $this->getEntityManager();
            $entityManager->remove($sides);
            $entityManager->flush();
            $entityData = $entityManager->contains($sides);
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
     * @return Sides|NULL Возвращает объект или ноль
     */
    public function findOneBySides(string $side, Participant $id_participant): ?Sides
    {
        return $this->findOneBy(['side' => $side, 'id_participant' => $id_participant]);
    }

    /**
     * @return Sides|NULL Возвращает объект или ноль
     */
    public function findOneByIdSides(int $id, Participant $id_participant): ?Sides
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
            'SELECT COUNT(s.id)
            FROM App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Sides s'
        );

        return $query->getResult();
    }

    /**
     * @return ARRAY|NULL Возвращает массив объектов или ноль
     */
    public function findBySides(Participant $id_participant): ?array
    {

        return $this->findBy(['id_participant' => $id_participant]);
    }

    /**
     * @return Sides|NULL Возвращает объект или ноль
     */
    public function findSides(int $id): ?Sides
    {

        return $this->find($id);
    }
}
