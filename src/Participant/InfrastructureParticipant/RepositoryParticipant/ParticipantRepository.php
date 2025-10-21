<?php

namespace App\Participant\InfrastructureParticipant\RepositoryParticipant;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use App\Participant\DomainParticipant\RepositoryInterfaceParticipant\ParticipantRepositoryInterface;

/**
 * @extends ServiceEntityRepository<Participant>
 */
class ParticipantRepository extends ServiceEntityRepository implements PasswordUpgraderInterface, ParticipantRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participant::class);
    }

    /**
     * @return int Возвращается число дублей 
     */
    public function numberDoubles(array $array): int
    {

        return $this->count($array);
    }

    /**
     * @return array Возвращается массив с данными об успешном сохранении пользователя 
     */
    public function save(Participant $participant): int
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($participant);
        $entityManager->flush();

        $entityData = $entityManager->getUnitOfWork()->getOriginalEntityData($participant);

        $exists = $this->count(['id' => $entityData['id']]);
        if ($exists == 0) {
            $arr_data_errors = ['Error' => 'Данные в базе данных не сохранены'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $entityData['id'];
    }

    /**
     * @return int Возвращается массив с данными об успешном изменения поставщика 
     */
    public function edit(Participant $participant): int
    {
        $entityManager = $this->getEntityManager();
        $entityManager->flush();
        $entityData = $entityManager->getUnitOfWork()->getOriginalEntityData($participant);
        //dd($participant);
        if (
            $participant->getEmail() != $entityData['email'] &&
            $participant->getRoles()[0] != $entityData['roles'][0] &&
            $participant->getPassword() != $entityData['password'] &&
            $participant->isVerified() != $entityData['isVerified']
        ) {
            $arr_data_errors = ['Error' => 'Данные в базе данных не изменены'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $participant->getId();
    }

    /**
     * @return int Возвращается массив с данными об удаление поставщика 
     */
    public function delete(Participant $participant): int
    {
        try {

            $entityManager = $this->getEntityManager();
            $entityManager->remove($participant);
            $entityManager->flush();
            $entityData = $entityManager->contains($participant);
            if ($entityData != false) {
                $arr_data_errors = ['Error' => 'Данные в базе данных не удалены'];
                $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
                throw new UnprocessableEntityHttpException($json_arr_data_errors);
            }
        } catch (\Exception $e) {
            if (!empty($e)) {
                $arr_data_errors =
                    ['Error' => 'Удаление запрещено, используется в других таблицах.'];
                $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
                throw new UnprocessableEntityHttpException($json_arr_data_errors);
            }
        }

        return 0;
    }

    /**
     * @return Participant[]|NULL Возвращает массив объектов поставщиков или ноль
     */
    public function findAllParticipant(): ?array
    {
        return $this->findAll();
    }

    /**
     * @return Participant|NULL Возвращает объектов или ноль
     */
    public function findParticipant($id): ?Participant
    {
        return $this->find($id);
    }

    /**
     * @return Participant|NULL Возвращает объект или ноль
     */
    public function findOneByParticipant(string $email): ?Participant
    {
        return $this->findOneBy(['email' => $email]);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Participant) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }
}
