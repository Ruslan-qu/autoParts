<?php

namespace App\PartNumbers\InfrastructurePartNumbers\RepositoryPartNumbers;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\OriginalRooms;

/**
 * @extends ServiceEntityRepository<OriginalRooms>
 */
class OriginalRoomsRepository extends ServiceEntityRepository
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
    public function save(PartNumbersFromManufacturers $partNumbersFromManufacturers): array
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($partNumbersFromManufacturers);
        $entityManager->flush();

        $entityData = $entityManager->getUnitOfWork()->getOriginalEntityData($partNumbersFromManufacturers);

        $exists_counterparty = $this->count($entityData);
        if ($exists_counterparty == 0) {
            $arr_data_errors = ['Error' => 'Данные в базе данных не сохранены'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }
        return $successfully = ['save' => 'Поставщик успешно сохранен'];
    }
}
