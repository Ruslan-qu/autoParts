<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DTOCommands\DTOAutoPartsWarehouseCommand;

use DateTime;
use Symfony\Component\TypeInfo\TypeResolver\TypeResolver;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse;

abstract class MapAutoPartsWarehouseCommand
{

    public function __construct(array $data = [])
    {
        $this->load($data);
    }

    private function load(array $data)
    {
        $typeResolver = TypeResolver::create();
        //dd($data);
        foreach ($data as $key => $value) {

            if (!empty($value)) {

                $type = $typeResolver->resolve(new \ReflectionProperty(AutoPartsWarehouse::class, $key))
                    ->getBaseType()
                    ->getTypeIdentifier()
                    ->value;

                if ($type == 'double' || $type == 'float') {
                    $value = $value * 100;
                }

                settype($value, $type);

                if ($type == 'object') {

                    $className = $typeResolver->resolve(new \ReflectionProperty(AutoPartsWarehouse::class, $key))
                        ->getBaseType()
                        ->getClassName();

                    if ($className !== get_class($value)) {

                        $arr_data_errors = ['Error' => 'Значение ' . $key .
                            ' должно быть объектом класса ' . $className . '.'];
                        $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
                        throw new UnprocessableEntityHttpException($json_arr_data_errors);
                    }
                }


                $this->$key = $value;
            }
        }
    }
}
