<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\DTOQuery\DTOOriginalRoomsQuery;

use Symfony\Component\TypeInfo\TypeResolver\TypeResolver;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\OriginalRooms;

abstract class OriginalRoomsQuery
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

                $type = $typeResolver->resolve(new \ReflectionProperty(OriginalRooms::class, $key))
                    ->getBaseType()
                    ->getTypeIdentifier()
                    ->value;
                settype($value, $type);
                if ($type == 'object') {

                    $className = $typeResolver->resolve(new \ReflectionProperty(OriginalRooms::class, $key))
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
