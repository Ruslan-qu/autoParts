<?php

namespace App\PartNumbers\ApplicationPartNumbers\QuerySides\DeleteSidesQuery;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\ApplicationPartNumbers\QuerySides\DTOQuery\DTOSidesQuery\SidesQuery;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\SidesRepositoryInterface;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\CarBrandsRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryCarBrands\DTOQuery\DTOCarBrandsQuery\CarBrandsQuery;

final class FindSidesQueryHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private SidesRepositoryInterface $sidesRepositoryInterface
    ) {}

    public function handler(SidesQuery $sidesQuery): ?array
    {
        $id = $sidesQuery->getId();
        $this->inputErrorsPartNumbers->emptyData($id);

        $find_sides['sides'] = $this->sidesRepositoryInterface->findSides($id);
        $this->inputErrorsPartNumbers->emptyEntity($find_sides);

        return $find_sides;
    }
}
