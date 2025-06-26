<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryCarBrands\SearchCarBrandsQuery;

use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\CarBrandsRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryCarBrands\DTOQuery\DTOPartNameQuery\CarBrandsQuery;

final class FindByCarBrandsQueryHandler
{

    public function __construct(
        private CarBrandsRepositoryInterface $carBrandsRepositoryInterface
    ) {}

    public function handler(CarBrandsQuery $carBrandsQuery): ?array
    {
        $id_participant = $carBrandsQuery->getIdParticipant();
        $findAllCarBrands = $this->carBrandsRepositoryInterface
            ->findByCarBrands($id_participant);

        return $findAllCarBrands;
    }
}
