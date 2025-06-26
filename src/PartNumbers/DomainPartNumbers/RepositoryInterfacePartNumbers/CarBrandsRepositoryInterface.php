<?php

namespace App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\CarBrands;

interface  CarBrandsRepositoryInterface
{
    public function save(CarBrands $carBrands): int;

    public function edit(CarBrands $carBrands): array;

    public function delete(CarBrands $carBrands): ?array;

    public function numberDoubles(array $array): int;

    public function findOneByCarBrands(string $carBrand, Participant $id_participant): ?CarBrands;

    public function findOneByIdCarBrands(int $id, Participant $id_participant): ?CarBrands;

    public function countId(): ?array;

    public function findByCarBrands(Participant $id_participant): ?array;

    public function findCarBrands(int $id): ?CarBrands;
}
