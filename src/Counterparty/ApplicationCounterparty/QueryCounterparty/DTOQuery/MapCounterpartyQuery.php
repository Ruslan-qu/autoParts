<?php

namespace App\Counterparty\ApplicationCounterparty\QueryCounterparty\DTOQuery;

use ReflectionProperty;
use App\Counterparty\ApplicationCounterparty\Errors\InputErrors;
use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;

abstract class MapCounterpartyQuery
{

    public function __construct(array $data = [])
    {
        $this->load($data);
    }

    private function load(array $data)
    {

        foreach ($data as $key => $value) {

            if (!empty($value)) {

                $input_errors = new InputErrors;
                $input_errors->propertyExistsEntity(Counterparty::class, $key, 'Counterparty');

                $refl = new ReflectionProperty(Counterparty::class, $key);
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
