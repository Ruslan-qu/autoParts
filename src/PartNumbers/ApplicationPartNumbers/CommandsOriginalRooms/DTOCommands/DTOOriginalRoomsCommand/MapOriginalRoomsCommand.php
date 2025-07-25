<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsOriginalRooms\DTOCommands\DTOOriginalRoomsCommand;

use Symfony\Component\TypeInfo\TypeResolver\TypeResolver;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\OriginalRooms;

abstract class MapOriginalRoomsCommand
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

                $input_errors = new InputErrorsPartNumbers;
                $input_errors->propertyExistsEntity(OriginalRooms::class, $key, 'OriginalRooms');

                $type = $typeResolver->resolve(new \ReflectionProperty(OriginalRooms::class, $key))
                    ->getBaseType()
                    ->getTypeIdentifier()
                    ->value;
                settype($value, $type);
                if ($type == 'object') {

                    $className = $typeResolver->resolve(new \ReflectionProperty(OriginalRooms::class, $key))
                        ->getBaseType()
                        ->getClassName();

                    $input_errors->comparingClassNames($className, $value, $key);
                }

                $this->$key = $value;
            }
        }
    }
}
