<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DTOCommands\DTOAutoPartsFileCommand;

use Symfony\Component\TypeInfo\TypeResolver\TypeResolver;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse;

abstract class MapAutoPartsFileCommand
{

    public function __construct(array $data = [])
    {
        $this->load($data);
    }

    private function load(array $data)
    {
        $typeResolver = TypeResolver::create();

        foreach ($data as $key => $value) {

            if (!empty($value)) {

                $input_errors = new InputErrorsAutoPartsWarehouse;

                $type = $typeResolver->resolve(new \ReflectionProperty(AutoPartsWarehouse::class, $key))
                    ->getBaseType()
                    ->getTypeIdentifier()
                    ->value;

                settype($value, $type);

                $this->$key = $value;
            }
        }
    }
}
