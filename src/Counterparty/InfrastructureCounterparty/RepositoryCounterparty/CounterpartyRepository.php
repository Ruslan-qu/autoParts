<?php

namespace App\Counterparty\InfrastructureCounterparty\RepositoryCounterparty;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

        $number_doubles = 0;
        foreach ($array as $key => $value) {
            $number_doubles = $number_doubles + $this->count([$key => $value]);
        }
        return $number_doubles;
    }

    /**
     * @return array Возвращается массив с данными об успешном сохранении контроагента 
     */
    public function save($entity_counterparty): array
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($entity_counterparty);
        $entityManager->flush();

        return $successfully = ['save' => 'Контрагент успешно сохранен'];
        //dd($entityManager);
    }

    /**
     * @return array Возвращается массив с данными об успешном изменения контроагента 
     */
    public function edit(): array
    {
        $entityManager = $this->getEntityManager();
        $entityManager->flush();

        return $successfully = ['edit' => 'Контрагент успешно изменен'];
        //dd($entityManager);
    }

    /**
     * @return Counterparty[]|NULL Возвращает массив объектов-контрагентов или ноль
     */
    public function findAllCounterparty(): ?array
    {
        return $this->findBy([], ['id' => 'ASC']);
    }

    /**
     * @return Counterparty[]|NULL Возвращает массив объектов-контрагентов или ноль
     */
    public function findOneByCounterparty($name_counterparty): ?array
    {
        return $this->findBy(['name_counterparty' => $name_counterparty], ['id' => 'ASC']);
    }

    /**
     * @return Counterparty|NULL Возвращает объект контрагента или ноль
     */
    public function findCounterparty($id): ?Counterparty
    {
        return $this->find($id);
    }
}
