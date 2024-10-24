<?php

namespace App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\FormAutoPartsWarehouse\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class EntityHiddenType extends HiddenType

{
    public function getParent(): string
    {
        return EntityType::class;
    }
}
