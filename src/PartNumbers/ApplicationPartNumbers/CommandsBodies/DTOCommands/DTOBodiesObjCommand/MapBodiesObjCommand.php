<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsBodies\DTOCommands\DTOBodiesObjCommand;

use Symfony\Component\TypeInfo\Type;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Bodies;

abstract class MapBodiesObjCommand
{
    public function __construct(array $data = [])
    {
        $this->load($data);
    }

    private function load(array $data)
    {
        foreach ($data as $key => $value) {

            if (!empty($value)) {
                $type = Type::object(Bodies::class);
                $className = $type->getClassName();
                $input_errors = new InputErrorsPartNumbers;
                $input_errors->comparingClassNames($className, $value, $key);
                $this->$key = $value;
            }
        }
    }
}
