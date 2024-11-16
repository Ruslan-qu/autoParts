<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\DTOQuery\DTOOriginalRoomsQuery;

use Symfony\Component\TypeInfo\TypeResolver\TypeResolver;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\OriginalRooms;

abstract class MapOriginalRoomsQuery
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

                $this->$key = $value;
            }
        }
    }
}
