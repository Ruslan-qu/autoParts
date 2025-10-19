<?php

namespace App\Participant\ApplicationParticipant\CommandsParticipant\DTOCommands\DTOParticipantObjCommand;

use Symfony\Component\TypeInfo\Type;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\Participant\ApplicationParticipant\ErrorsParticipant\InputErrorsParticipant;

abstract class MapParticipantObjCommand
{

    public function __construct(array $data = [])
    {
        $this->load($data);
    }

    private function load(array $data)
    {
        foreach ($data as $key => $value) {

            if (!empty($value)) {
                $type = Type::object(Participant::class);
                $className = $type->getClassName();
                $input_errors = new InputErrorsParticipant;
                $input_errors->comparingClassNames($className, $value, $key);
                $this->$key = $value;
            }
        }
    }
}
