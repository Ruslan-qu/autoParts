<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsOriginalRooms\DTOCommands\DTOOriginalRoomsObjCommand;

use Symfony\Component\TypeInfo\Type;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\OriginalRooms;

abstract class MapOriginalRoomsObjCommand
{

    public function __construct(array $data = [])
    {
        $this->load($data);
    }

    private function load(array $data)
    {
        foreach ($data as $key => $value) {

            if (!empty($value)) {
                $type = Type::object(OriginalRooms::class);
                $className = $type->getBaseType()->getClassName();
                $input_errors = new InputErrorsPartNumbers;
                $input_errors->comparingClassNames($className, $value, $key);
                $this->$key = $value;
            }
        }
    }
}
