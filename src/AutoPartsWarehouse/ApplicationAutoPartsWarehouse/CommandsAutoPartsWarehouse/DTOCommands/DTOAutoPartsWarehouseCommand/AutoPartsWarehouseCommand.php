<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DTOCommands\DTOAutoPartsWarehouseCommand;

use Symfony\Component\TypeInfo\TypeResolver\TypeResolver;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartNumbersFromManufacturers;

abstract class AutoPartsWarehouseCommand
{

    public function __construct(array $data = [])
    {
        $this->load($data);
    }

    private function load(array $data)
    {
        $typeResolver = TypeResolver::create();
        dd(gettype($data['price']));
        foreach ($data as $key => $value) {

            if (!empty($value)) {

                $type = $typeResolver->resolve(new \ReflectionProperty(PartNumbersFromManufacturers::class, $key))
                    ->getBaseType()
                    ->getTypeIdentifier()
                    ->value;


                if ($type == 'object') {

                    $className = $typeResolver->resolve(new \ReflectionProperty(PartNumbersFromManufacturers::class, $key))
                        ->getBaseType()
                        ->getClassName();
                    if ($className !== get_class($value)) {

                        $arr_data_errors = ['Error' => 'Значение ' . $value->scalar .
                            ' должно быть объектом класса ' . $className . '.'];
                        $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
                        throw new UnprocessableEntityHttpException($json_arr_data_errors);
                    }
                }

                if ($type == 'double' || $type == 'float') {
                    # code...
                }
                settype($value, $type);
                $this->$key = $value;
            }
        }
    }
}
