<?php

namespace App\Participant\ApplicationParticipant\QueryParticipant\DTOQuery\DTOParticipantQuery;

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
        $typeResolver = TypeResolver::create();

        foreach ($data as $key => $value) {

            if (!empty($value)) {

                $input_errors = new InputErrorsParticipant;
                $input_errors->propertyExistsEntity(Participant::class, $key, 'Participant');

                $type = $typeResolver->resolve(new \ReflectionProperty(Participant::class, $key))
                    ->getBaseType()
                    ->getTypeIdentifier()
                    ->value;

                if ($type == 'object') {

                    $className = $typeResolver->resolve(new \ReflectionProperty(Participant::class, $key))
                        ->getBaseType()
                        ->getClassName();

                    $input_errors->comparingClassNames($className, $value, $key);
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
