<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\DTOQuery\DTOPartNameQuery;

use Symfony\Component\TypeInfo\TypeResolver\TypeResolver;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartName;

abstract class MapPartNameQuery
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
                $input_errors->propertyExistsEntity(PartName::class, $key, 'PartName');

                $type = $typeResolver->resolve(new \ReflectionProperty(PartName::class, $key))
                    ->getBaseType()
                    ->getTypeIdentifier()
                    ->value;
                settype($value, $type);

                $this->$key = $value;
            }
        }
    }
}
