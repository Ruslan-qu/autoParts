<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryPartNames\DTOQuery\DTOPartNameQuery;

use ReflectionProperty;
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

        $input_errors = new InputErrorsPartNumbers;

        foreach ($data as $key => $value) {

            if (!empty($value)) {


                $input_errors->propertyExistsEntity(PartName::class, $key, 'PartName');

                $refl = new ReflectionProperty(PartName::class, $key);
                $type = $refl->getType()->getName();

                if (is_object($value)) {

                    $input_errors->comparingClassNames($type, $value, $key);
                    $type = 'object';
                }

                settype($value, $type);
                $this->$key = $value;
            }
        }
    }
}
