<?php

namespace App\PartNumbers\InfrastructurePartNumbers\RepositoryPartNumbers;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\CarBrands;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\CarBrandsRepositoryInterface;

/**
 * @extends ServiceEntityRepository<CarBrands>
 */
class CarBrandsRepository extends ServiceEntityRepository implements CarBrandsRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarBrands::class);
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
    public function save(CarBrands $carBrands): int
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($carBrands);
        $entityManager->flush();

        $entityData = $entityManager->getUnitOfWork()->getOriginalEntityData($carBrands);

        $exists_part_name = $this->count($entityData);
        if ($exists_part_name == 0) {
            $arr_data_errors = ['Error' => 'Данные в базе данных не сохранены'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $entityData['id'];
    }

    /**
     * @return array Возвращается массив с данными об успешном изменения  
     */
    public function edit(CarBrands $carBrands): array
    {
        $entityManager = $this->getEntityManager();
        $entityManager->flush();

        $exists_part_name = $this->count(['car_brand' => $carBrands->getCarBrand()]);
        if ($exists_part_name == 0) {
            $arr_data_errors = ['Error' => 'Данные в базе данных не изменены'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $successfully = ['edit' => $carBrands->getId()];
    }

    /**
     * @return array Возвращается массив с данными об удаление 
     */
    public function delete(CarBrands $carBrands): array
    {
        try {

            $entityManager = $this->getEntityManager();
            $entityManager->remove($carBrands);
            $entityManager->flush();
            $entityData = $entityManager->contains($carBrands);
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
     * @return CarBrands|NULL Возвращает объект или ноль
     */
    public function findOneByCarBrands(string $car_brand, Participant $id_participant): ?CarBrands
    {
        return $this->findOneBy(['car_brand' => $car_brand, 'id_participant' => $id_participant]);
    }

    /**
     * @return CarBrands|NULL Возвращает объект или ноль
     */
    public function findOneByIdCarBrands(int $id, Participant $id_participant): ?CarBrands
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
            'SELECT COUNT(c.id)
            FROM App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\CarBrands c'
        );

        return $query->getResult();
    }

    /**
     * @return ARRAY|NULL Возвращает массив объектов или ноль
     */
    public function findByCarBrands(Participant $id_participant): ?array
    {

        return $this->findBy(['id_participant' => $id_participant]);
    }

    /**
     * @return CarBrands|NULL Возвращает объект или ноль
     */
    public function findCarBrands(int $id): ?CarBrands
    {

        return $this->find($id);
    }
}
