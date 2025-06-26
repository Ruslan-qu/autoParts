<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryCarBrands\DTOQuery\DTOPartNameQuery;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\PartNumbers\ApplicationPartNumbers\QueryCarBrands\DTOQuery\DTOPartNameQuery\MapCarBrandsQuery;

final class CarBrandsQuery extends MapCarBrandsQuery
{
    protected ?int $id = null;

    protected ?string $car_brand = null;

    protected ?Participant $id_participant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarBrand(): ?string
    {
        return $this->car_brand;
    }

    public function getIdParticipant(): ?Participant
    {
        return $this->id_participant;
    }
}
