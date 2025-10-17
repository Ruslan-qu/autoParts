<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsPartNames\DTOCommands\DTOPartNameCommand;

use ReflectionProperty;
use Symfony\Component\TypeInfo\TypeResolver\TypeResolver;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartName;

abstract class MapPartNameCommand
{

    public function __construct(array $data = [])
    {
        $this->load($data);
    }

    private function load(array $data)
    {
        unset($data['part_name']);
        foreach ($data as $key => $value) {

            if (!empty($value)) {

                $input_errors = new InputErrorsPartNumbers;
                $input_errors->propertyExistsEntity(PartName::class, $key, 'PartName');

                $refl = new ReflectionProperty(PartName::class, $key);

                $type = $refl->getType()->getName();

                dd($refl->getType());
                if ($type == 'object') {
                    dd($refl);
                    /* $className = $typeResolver->resolve(new \ReflectionProperty(PartName::class, $key))
                        ->getBaseType()
                        ->getClassName();*/

                    $input_errors->comparingClassNames($className, $value, $key);
                }
                settype($value, $type);
                $this->$key = $value;
            }
        }
    }
}
