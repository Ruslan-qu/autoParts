<?php

namespace App\Participant\ApplicationParticipant\QueryParticipant\DTOQuery\DTOParticipantQuery;

use ReflectionProperty;
use Symfony\Component\TypeInfo\TypeResolver\TypeResolver;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\Participant\ApplicationParticipant\ErrorsParticipant\InputErrorsParticipant;

abstract class MapParticipantQuery
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

                $input_errors->propertyExistsEntity(Participant::class, $key, 'Participant');

                $refl = new ReflectionProperty(Participant::class, $key);
                $type = $refl->getType()->getName();

                if (is_object($value)) {

                    $input_errors->comparingClassNames($type, $value, $key);
                    $type = 'object';
                }

                if ($type == 'array') {

                    $value = [$value];
                }

                settype($value, $type);
                $this->$key = $value;
            }
        }
    }
}
