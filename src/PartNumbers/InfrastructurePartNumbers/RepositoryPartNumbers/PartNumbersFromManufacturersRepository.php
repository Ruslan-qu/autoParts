<?php

namespace App\PartNumbers\InfrastructurePartNumbers\RepositoryPartNumbers;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
     * @return array Возвращается массив с данными об успешном сохранении поставщика 
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

    /**
     * @return array Возвращается массив с данными об успешном изменения поставщика 
     */
    public function edit(PartNumbersFromManufacturers $partNumbersFromManufacturers): array
    {
        $entityManager = $this->getEntityManager();
        $entityManager->flush();
        $entityData = $entityManager->getUnitOfWork()->getOriginalEntityData($partNumbersFromManufacturers);

        $exists_counterparty = $this->count($entityData);
        if ($exists_counterparty == 0) {
            $arr_data_errors = ['Error' => 'Данные в базе данных не изменены'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $successfully = ['edit' => 'Поставщик успешно изменен'];
    }

    /**
     * @return array Возвращается массив с данными об удаление поставщика 
     */
    public function delete(PartNumbersFromManufacturers $partNumbersFromManufacturers): array
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($partNumbersFromManufacturers);
        $entityManager->flush();

        $entityData = $entityManager->contains($partNumbersFromManufacturers);
        if ($entityData != false) {
            $arr_data_errors = ['Error' => 'Данные в базе данных не удалены'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }


        return $successfully = ['delete' => 'Поставщик удален'];
    }


    /**
     * @return PartNumbersFromManufacturers[]|NULL Возвращает массив объектов поставщиков или ноль
     */
    public function findAllPartNumbersFromManufacturers(): ?array
    {
        return $this->findBy([], ['id' => 'ASC']);
    }

    /**
     * @return PartNumbersFromManufacturers[]|NULL Возвращает массив объектов Поставщиков или ноль
     */
    public function findOneByPartNumbersFromManufacturers(string $name_counterparty): ?array
    {
        return $this->findBy(['name_counterparty' => $name_counterparty], ['id' => 'ASC']);
    }

    /**
     * @return PartNumbersFromManufacturers|NULL Возвращает объект поставщика или ноль
     */
    public function findPartNumbersFromManufacturers(int $id): ?PartNumbersFromManufacturers
    {
        return $this->find($id);
    }
}
