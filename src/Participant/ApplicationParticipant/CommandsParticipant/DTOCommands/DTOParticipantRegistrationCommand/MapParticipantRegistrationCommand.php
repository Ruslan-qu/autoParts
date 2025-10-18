<?php

namespace App\Participant\ApplicationParticipant\CommandsParticipant\DTOCommands\DTOParticipantRegistrationCommand;

use ReflectionProperty;
use Symfony\Component\TypeInfo\TypeResolver\TypeResolver;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\Participant\ApplicationParticipant\ErrorsParticipant\InputErrorsParticipant;

abstract class MapParticipantRegistrationCommand
{

    public function __construct(array $data = [])
    {
        $this->load($data);
    }

    private function load(array $data)
    {
        $input_errors = new InputErrorsParticipant;

        unset($data['button_registration_participant']);

        foreach ($data as $key => $value) {

            $input_errors->propertyExistsEntity(Participant::class, $key, 'Participant');

            if (!empty($value->getData())) {

                $value = $value->getData();

                $refl = new ReflectionProperty(Participant::class, $key);
                $type = $refl->getType()->getName();

                settype($value, $type);
                $this->$key = $value;
            }
        }
    }
}
