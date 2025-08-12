<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsReplacingOriginalNumbers\DTOCommands\DTOReplacingOriginalNumbersCommand;

use Symfony\Component\TypeInfo\TypeResolver\TypeResolver;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\ReplacingOriginalNumbers;

abstract class MapReplacingOriginalNumbersCommand
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
                $input_errors->propertyExistsEntity(ReplacingOriginalNumbers::class, $key, 'ReplacingOriginalNumbers');

                $type = $typeResolver->resolve(new \ReflectionProperty(ReplacingOriginalNumbers::class, $key))
                    ->getBaseType()
                    ->getTypeIdentifier()
                    ->value;
                settype($value, $type);
                if ($type == 'object') {

                    $className = $typeResolver->resolve(new \ReflectionProperty(ReplacingOriginalNumbers::class, $key))
                        ->getBaseType()
                        ->getClassName();

                    $input_errors->comparingClassNames($className, $value, $key);
                }

                $this->$key = $value;
            }
        }
    }
}
