<?php

namespace App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\RepositoryAutoPartsWarehouse;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
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
     * @return array Возвращается массив с данными об успешном сохранении
     */
    public function save(AutoPartsWarehouse $autoPartsWarehouse): array
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($autoPartsWarehouse);
        $entityManager->flush();

        $entityData = $entityManager->getUnitOfWork()->getOriginalEntityData($autoPartsWarehouse);

        $exists_part_numbers = $this->count($entityData);
        if ($exists_part_numbers == 0) {
            $arr_data_errors = ['Error' => 'Данные в базе данных не сохранены'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }
        return $successfully = ['save' => $entityData['id']];
    }
}
