<?php

namespace App\Counterparty\ApplicationCounterparty\CommandsCounterparty\DTOCommands;

use Symfony\Component\TypeInfo\TypeResolver\TypeResolver;
use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;

abstract class CounterpartyCommand
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

                $type = $typeResolver->resolve(new \ReflectionProperty(Counterparty::class, $key))
                    ->getBaseType()
                    ->getTypeIdentifier()
                    ->value;
                settype($value, $type);
                $this->$key = $value;
            }
        }
    }
}
