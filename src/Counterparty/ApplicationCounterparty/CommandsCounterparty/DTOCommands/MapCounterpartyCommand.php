<?php

namespace App\Counterparty\ApplicationCounterparty\CommandsCounterparty\DTOCommands;

use Symfony\Component\TypeInfo\TypeResolver\TypeResolver;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;

abstract class MapCounterpartyCommand
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

                if (!property_exists(Counterparty::class, $key)) {

                    $arr_data_errors = ['Error' => 'Свойство ' . $key .
                        '  не существует в Counterparty объекте.'];
                    $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
                    throw new UnprocessableEntityHttpException($json_arr_data_errors);
                }

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
