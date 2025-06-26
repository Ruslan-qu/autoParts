<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsCarBrands\DeleteCarBrandsCommand;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\CarBrandsRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsCarBrands\DTOCommands\DTOCarBrandsObjCommand\CarBrandsObjCommand;

final class DeleteCarBrandsCommandHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private CarBrandsRepositoryInterface $carBrandsRepositoryInterface,
    ) {}

    public function handler(CarBrandsObjCommand $carBrandsObjCommand): ?int
    {
        $car_brands = $carBrandsObjCommand->getCarBrands();

        $successfully_delete = $this->carBrandsRepositoryInterface->delete($car_brands);

        return $successfully_delete['delete'];
    }
}
