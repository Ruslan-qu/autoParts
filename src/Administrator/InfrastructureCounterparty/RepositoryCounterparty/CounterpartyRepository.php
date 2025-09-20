<?php

namespace App\Counterparty\InfrastructureCounterparty\RepositoryCounterparty;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
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
    public function save(Counterparty $entity_counterparty): int
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($entity_counterparty);
        $entityManager->flush();

        $entityData = $entityManager->getUnitOfWork()->getOriginalEntityData($entity_counterparty);

        $exists_counterparty = $this->count(['id' => $entityData['id']]);
        if ($exists_counterparty == 0) {
            $arr_data_errors = ['Error' => 'Данные в базе данных не сохранены'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }
        return $entityData['id'];
    }

    /**
     * @return array Возвращается массив с данными об успешном изменения поставщика 
     */
    public function edit(Counterparty $edit_counterparty): int
    {
        $entityManager = $this->getEntityManager();
        $entityManager->flush();
        $entityData = $entityManager->getUnitOfWork()->getOriginalEntityData($edit_counterparty);
        unset($entityData['id_participant_id']);

        $exists_counterparty = $this->count($entityData);
        if ($exists_counterparty == 0) {
            $arr_data_errors = ['Error' => 'Данные в базе данных не изменены'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $entityData['id'];
    }

    /**
     * @return array Возвращается массив с данными об удаление поставщика 
     */
    public function delete(Counterparty $counterparty): int
    {
        try {

            $entityManager = $this->getEntityManager();
            $entityManager->remove($counterparty);
            $entityManager->flush();
            $entityData = $entityManager->contains($counterparty);
            if ($entityData != false) {
                $arr_data_errors = ['Error' => 'Данные в базе данных не удалены'];
                $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
                throw new UnprocessableEntityHttpException($json_arr_data_errors);
            }
        } catch (\Exception $e) {
            if (!empty($e)) {
                $arr_data_errors =
                    ['Error' => 'Удаление запрещено, используется в таблице ' . '"' . 'Склад или Продажи' . '".'];
                $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
                throw new UnprocessableEntityHttpException($json_arr_data_errors);
            }
        }

        return 0;
    }

    /**
     * @return Counterparty[]|NULL Возвращает массив объектов поставщиков или ноль
     */
    public function findAllCounterparty(): ?array
    {
        return $this->findAll();
    }

    /**
     * @return Counterparty[]|NULL Возвращает массив объектов поставщиков или ноль
     */
    public function findByCounterparty(Participant $id_participant): ?array
    {
        return $this->findBy(['id_participant' => $id_participant], ['name_counterparty' => 'ASC']);
    }

    /**
     * @return Counterparty|NULL Возвращает массив объектов Поставщиков или ноль
     */
    public function findOneByCounterparty(string $name_counterparty, Participant $id_participant): ?Counterparty
    {
        return $this->findOneBy(
            [
                'name_counterparty' => $name_counterparty,
                'id_participant' => $id_participant
            ]
        );
    }

    /**
     * @return Counterparty|NULL Возвращает массив объектов Поставщиков или ноль
     */
    public function findOneByEmailCounterparty(string $mail_counterparty, Participant $id_participant): ?Counterparty
    {
        return $this->findOneBy(
            [
                'mail_counterparty' => $mail_counterparty,
                'id_participant' => $id_participant
            ]
        );
    }

    /**
     * @return Counterparty|NULL Возвращает объект поставщика или ноль
     */
    public function findOneByIdCounterparty(int $id, Participant $id_participant): ?Counterparty
    {

        return $this->findOneBy(['id' => $id, 'id_participant' => $id_participant]);
    }

    /**
     * @return Counterparty|NULL Возвращает объектов или ноль
     */
    public function findCounterparty($id): ?Counterparty
    {
        return $this->find($id);
    }
}
