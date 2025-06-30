<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryCarBrands\DeleteCarBrandsQuery;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\CarBrandsRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryCarBrands\DTOQuery\DTOPartNameQuery\CarBrandsQuery;

final class FindCarBrandsQueryHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private CarBrandsRepositoryInterface $carBrandsRepositoryInterface
    ) {}

    public function handler(CarBrandsQuery $carBrandsQuery): ?array
    {
        $id = $carBrandsQuery->getId();
        $this->inputErrorsPartNumbers->emptyData($id);

        $find_car_brand['car_brands'] = $this->carBrandsRepositoryInterface->findCarBrands($id);
        $this->inputErrorsPartNumbers->emptyEntity($find_car_brand);

        return $find_car_brand;
    }
}
