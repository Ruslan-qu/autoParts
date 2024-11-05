<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOPartNumbersCommand;

use Symfony\Component\TypeInfo\TypeResolver\TypeResolver;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartNumbersFromManufacturers;

abstract class MapPartNumbersCommand
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

                if (!property_exists(PartNumbersFromManufacturers::class, $key)) {

                    $arr_data_errors = ['Error' => 'Свойство ' . $key .
                        '  не существует в PartNumbersFromManufacturers объекте.'];
                    $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
                    throw new UnprocessableEntityHttpException($json_arr_data_errors);
                }

                $type = $typeResolver->resolve(new \ReflectionProperty(PartNumbersFromManufacturers::class, $key))
                    ->getBaseType()
                    ->getTypeIdentifier()
                    ->value;
                settype($value, $type);
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

                $this->$key = $value;
            }
        }
    }
}
