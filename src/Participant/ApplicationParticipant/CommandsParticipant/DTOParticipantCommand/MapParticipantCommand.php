<?php

namespace App\Participant\ApplicationParticipant\CommandsParticipant\DTOParticipantCommand;

use Symfony\Component\TypeInfo\TypeResolver\TypeResolver;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\Participant\ApplicationParticipant\ErrorsParticipant\InputErrorsParticipant;

abstract class MapParticipantCommand
{

    public function __construct(array $data = [])
    {
        $this->load($data);
    }

    private function load(array $data)
    {
        $typeResolver = TypeResolver::create();
        $input_errors = new InputErrorsParticipant;

        foreach ($data as $key => $value) {

            $input_errors->propertyExistsEntity(Participant::class, $key, 'Participant');

            if (!empty($value)) {

                $type = $typeResolver->resolve(new \ReflectionProperty(Participant::class, $key))
                    ->getBaseType()
                    ->getTypeIdentifier()
                    ->value;
                settype($value, $type);
                $this->$key = $value;
            }
        }
    }
}
