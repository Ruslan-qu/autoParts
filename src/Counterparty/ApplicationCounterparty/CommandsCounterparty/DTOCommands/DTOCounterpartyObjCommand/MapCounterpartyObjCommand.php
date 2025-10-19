<?php

namespace App\Counterparty\ApplicationCounterparty\CommandsCounterparty\DTOCommands\DTOCounterpartyObjCommand;

use Symfony\Component\TypeInfo\Type;
use App\Counterparty\ApplicationCounterparty\Errors\InputErrors;
use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;

abstract class MapCounterpartyObjCommand
{

    public function __construct(array $data = [])
    {
        $this->load($data);
    }

    private function load(array $data)
    {
        foreach ($data as $key => $value) {

            if (!empty($value)) {
                $type = Type::object(Counterparty::class);
                $className = $type->getClassName();
                $input_errors = new InputErrors;
                $input_errors->comparingClassNames($className, $value, $key);
                $this->$key = $value;
            }
        }
    }
}
