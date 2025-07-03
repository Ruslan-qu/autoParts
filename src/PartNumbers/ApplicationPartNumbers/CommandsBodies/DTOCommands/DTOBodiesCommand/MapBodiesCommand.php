<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsBodies\DTOCommands\DTOBodiesCommand;

use Symfony\Component\TypeInfo\TypeResolver\TypeResolver;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Bodies;

abstract class MapBodiesCommand
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
                $input_errors->propertyExistsEntity(Bodies::class, $key, 'Bodies');

                $type = $typeResolver->resolve(new \ReflectionProperty(Bodies::class, $key))
                    ->getBaseType()
                    ->getTypeIdentifier()
                    ->value;
                settype($value, $type);
                if ($type == 'object') {

                    $className = $typeResolver->resolve(new \ReflectionProperty(Bodies::class, $key))
                        ->getBaseType()
                        ->getClassName();

                    $input_errors->comparingClassNames($className, $value, $key);
                }

                $this->$key = $value;
            }
        }
    }
}
