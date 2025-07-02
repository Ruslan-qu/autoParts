<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryCarBrands\EditCarBrandsQuery;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\CarBrands;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\CarBrandsRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryCarBrands\DTOQuery\DTOCarBrandsQuery\CarBrandsQuery;


final class FindOneByIdCarBrandsQueryHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private CarBrandsRepositoryInterface $carBrandsRepositoryInterface
    ) {}

    public function handler(CarBrandsQuery $carBrandsQuery): ?CarBrands
    {

        $id = $carBrandsQuery->getId();
        $this->inputErrorsPartNumbers->emptyData($id);
        $participant = $carBrandsQuery->getIdParticipant();
        $this->inputErrorsPartNumbers->userIsNotIdentified($participant);

        $edit_car_brand = $this->carBrandsRepositoryInterface->findOneByIdCarBrands($id, $participant);
        $this->inputErrorsPartNumbers->emptyEntity($edit_car_brand);

        return $edit_car_brand;
    }
}
