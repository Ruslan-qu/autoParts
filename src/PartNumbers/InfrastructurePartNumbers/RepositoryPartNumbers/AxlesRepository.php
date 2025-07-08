<?php

namespace App\PartNumbers\InfrastructurePartNumbers\RepositoryPartNumbers;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Axles;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\AxlesRepositoryInterface;

/**
 * @extends ServiceEntityRepository<Axles>
 */
class AxlesRepository extends ServiceEntityRepository implements AxlesRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Axles::class);
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
    public function save(Axles $axles): int
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($axles);
        $entityManager->flush();

        $entityData = $entityManager->getUnitOfWork()->getOriginalEntityData($axles);

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
    public function edit(Axles $axles): array
    {
        $entityManager = $this->getEntityManager();
        $entityManager->flush();

        $exists_sides = $this->count(['axle' => $axles->getAxle()]);
        if ($exists_sides == 0) {
            $arr_data_errors = ['Error' => 'Данные в базе данных не изменены'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $successfully = ['edit' => $axles->getId()];
    }

    /**
     * @return array Возвращается массив с данными об удаление 
     */
    public function delete(Axles $axles): array
    {
        try {

            $entityManager = $this->getEntityManager();
            $entityManager->remove($axles);
            $entityManager->flush();
            $entityData = $entityManager->contains($axles);
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
     * @return Axles|NULL Возвращает объект или ноль
     */
    public function findOneByAxles(string $axle, Participant $id_participant): ?Axles
    {
        return $this->findOneBy(['axle' => $axle, 'id_participant' => $id_participant]);
    }

    /**
     * @return Axles|NULL Возвращает объект или ноль
     */
    public function findOneByIdAxles(int $id, Participant $id_participant): ?Axles
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
            'SELECT COUNT(a.id)
            FROM App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Axles a'
        );

        return $query->getResult();
    }

    /**
     * @return ARRAY|NULL Возвращает массив объектов или ноль
     */
    public function findByAxles(Participant $id_participant): ?array
    {

        return $this->findBy(['id_participant' => $id_participant]);
    }

    /**
     * @return Axles|NULL Возвращает объект или ноль
     */
    public function findAxles(int $id): ?Axles
    {

        return $this->find($id);
    }
}
