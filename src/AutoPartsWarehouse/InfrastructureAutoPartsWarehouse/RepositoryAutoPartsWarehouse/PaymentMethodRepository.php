<?php

namespace App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\RepositoryAutoPartsWarehouse;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\PaymentMethod;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\PaymentMethodRepositoryInterface;

/**
 * @extends ServiceEntityRepository<PaymentMethod>
 */
class PaymentMethodRepository extends ServiceEntityRepository implements PaymentMethodRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentMethod::class);
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
    public function save(PaymentMethod $payment_method): int
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($payment_method);
        $entityManager->flush();

        $entityData = $entityManager->getUnitOfWork()->getOriginalEntityData($payment_method);

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
    public function edit(PaymentMethod $payment_method): int
    {
        $entityManager = $this->getEntityManager();
        $entityManager->flush();
        $entityData = $entityManager->getUnitOfWork()->getOriginalEntityData($payment_method);
        unset($entityData['id_participant_id']);

        $exists = $this->count($entityData);
        if ($exists == 0) {
            $arr_data_errors = ['Error' => 'Данные в базе данных не изменены'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $entityData['id'];
    }

    /**
     * @return array Возвращается массив с данными об удаление поставщика 
     */
    public function delete(PaymentMethod $payment_method): int
    {
        try {

            $entityManager = $this->getEntityManager();
            $entityManager->remove($payment_method);
            $entityManager->flush();
            $entityData = $entityManager->contains($payment_method);
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
     * @return PaymentMethod[]|NULL Возвращает массив объектов поставщиков или ноль
     */
    public function findAllPaymentMethod(): ?array
    {
        return $this->findAll();
    }

    /**
     * @return PaymentMethod[]|NULL Возвращает массив объектов поставщиков или ноль
     */
    public function findByPaymentMethod(Participant $id_participant): ?array
    {
        return $this->findBy(['id_participant' => $id_participant], ['method' => 'ASC']);
    }

    /**
     * @return PaymentMethod|NULL Возвращает объект или ноль
     */
    public function findOneByPaymentMethod(string $method, Participant $id_participant): ?PaymentMethod
    {
        return $this->findOneBy(
            [
                'method' => $method,
                'id_participant' => $id_participant
            ]
        );
    }

    /**
     * @return PaymentMethod|NULL Возвращает объект или ноль
     */
    public function findOneByIdPaymentMethod(int $id, Participant $id_participant): ?PaymentMethod
    {

        return $this->findOneBy(['id' => $id, 'id_participant' => $id_participant]);
    }

    /**
     * @return PaymentMethod|NULL Возвращает объектов или ноль
     */
    public function findPaymentMethod($id): ?PaymentMethod
    {
        return $this->find($id);
    }

    /**
     * @return PaymentMethod|NULL Возвращает объектов или ноль
     */
    /* public function findOneByPaymentMethod(int|null $id): ?PaymentMethod
    {
        return $this->findOneBy(['id' => $id]);
    }*/
}
