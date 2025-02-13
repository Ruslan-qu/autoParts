<?php

namespace App\Counterparty\InfrastructureCounterparty\RepositoryCounterparty;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;
use App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty\CounterpartyRepositoryInterface;

/**
 * @extends ServiceEntityRepository<Counterparty>
 */
class CounterpartyRepository extends ServiceEntityRepository implements CounterpartyRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Counterparty::class);
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
    public function save(Counterparty $entity_counterparty): array
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($entity_counterparty);
        $entityManager->flush();

        $entityData = $entityManager->getUnitOfWork()->getOriginalEntityData($entity_counterparty);

        $exists_counterparty = $this->count($entityData);
        if ($exists_counterparty == 0) {
            $arr_data_errors = ['Error' => 'Данные в базе данных не сохранены'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }
        return $successfully = ['save' => $entityData['id']];
    }

    /**
     * @return array Возвращается массив с данными об успешном изменения поставщика 
     */
    public function edit(Counterparty $edit_counterparty): array
    {
        $entityManager = $this->getEntityManager();
        $entityManager->flush();
        $entityData = $entityManager->getUnitOfWork()->getOriginalEntityData($edit_counterparty);

        $exists_counterparty = $this->count($entityData);
        if ($exists_counterparty == 0) {
            $arr_data_errors = ['Error' => 'Данные в базе данных не изменены'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $successfully = ['edit' => $entityData['id']];
    }

    /**
     * @return array Возвращается массив с данными об удаление поставщика 
     */
    public function delete(Counterparty $entity_counterparty): array
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($entity_counterparty);
        $entityManager->flush();

        $entityData = $entityManager->contains($entity_counterparty);
        if ($entityData != false) {
            $arr_data_errors = ['Error' => 'Данные в базе данных не удалены'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $successfully = ['delete' => 0];
    }

    /**
     * @return Counterparty[]|NULL Возвращает массив объектов поставщиков или ноль
     */
    public function findAllCounterparty(): ?array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT a.name_counterparty
            FROM App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty a'
        );

        return $query->getResult();
    }

    /**
     * @return Counterparty[]|NULL Возвращает массив объектов поставщиков или ноль
     */
    public function findByCounterparty(): ?array
    {
        return $this->findBy([], ['id' => 'ASC']);
    }

    /**
     * @return Counterparty[]|NULL Возвращает массив объектов Поставщиков или ноль
     */
    public function findOneByCounterparty($name_counterparty): ?array
    {
        return $this->findBy(['name_counterparty' => $name_counterparty], ['id' => 'ASC']);
    }

    /**
     * @return Counterparty|NULL Возвращает объект поставщика или ноль
     */
    public function findCounterparty($id): ?Counterparty
    {
        return $this->find($id);
    }

    /**
     * @return Counterparty|NULL Возвращает объектов или ноль
     */
    public function findOneByIdCounterparty(string $name_counterparty): ?Counterparty
    {
        return $this->findOneBy(['name_counterparty' => $name_counterparty]);
    }
}
