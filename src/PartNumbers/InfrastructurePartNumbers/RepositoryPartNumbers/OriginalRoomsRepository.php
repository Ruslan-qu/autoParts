<?php

namespace App\PartNumbers\InfrastructurePartNumbers\RepositoryPartNumbers;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
     * @return OriginalRooms|NULL Возвращает массив объектов или ноль
     */
    public function findOneByOriginalRooms(string $original_number): ?OriginalRooms
    {
        return $this->findOneBy(['original_number' => $original_number]);
    }

    /**
     * @return OriginalRooms|NULL Возвращает объект или ноль
     */
    public function findOriginalRooms(int $id): ?OriginalRooms
    {
        return $this->find($id);
    }
}
