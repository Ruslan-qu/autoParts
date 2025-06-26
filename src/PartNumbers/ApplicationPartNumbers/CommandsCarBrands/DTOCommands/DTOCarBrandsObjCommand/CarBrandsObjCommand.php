<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsCarBrands\DTOCommands\DTOCarBrandsObjCommand;

use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\CarBrands;
use App\PartNumbers\ApplicationPartNumbers\CommandsCarBrands\DTOCommands\DTOCarBrandsObjCommand\MapCarBrandsObjCommand;

final class CarBrandsObjCommand extends MapCarBrandsObjCommand
{
    protected ?CarBrands $car_brands = null;

    public function getCarBrands(): ?CarBrands
    {
        return $this->car_brands;
    }
}
