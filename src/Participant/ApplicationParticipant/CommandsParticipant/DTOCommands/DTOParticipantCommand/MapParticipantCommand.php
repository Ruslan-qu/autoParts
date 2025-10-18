<?php

namespace App\Participant\ApplicationParticipant\CommandsParticipant\DTOCommands\DTOParticipantCommand;

use ReflectionProperty;
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
        $input_errors = new InputErrorsParticipant;

        foreach ($data as $key => $value) {

            if (!empty($value)) {

                $old_password = '';
                if ($key == 'old_password') {
                    $key = 'password';
                    $old_password = 'old_password';
                }

                $input_errors->propertyExistsEntity(Participant::class, $key, 'Participant');

                $refl = new ReflectionProperty(Participant::class, $key);
                $type = $refl->getType()->getName();

                if ($type == 'array') {
                    $value = [$value];
                }

                if (!empty($old_password)) {

                    $key = 'old_password';
                }

                settype($value, $type);
                $this->$key = $value;
            }
        }
    }
}
