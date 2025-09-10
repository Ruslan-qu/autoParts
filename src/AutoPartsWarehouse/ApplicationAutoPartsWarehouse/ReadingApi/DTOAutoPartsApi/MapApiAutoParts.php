<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingApi\DTOAutoPartsApi;

use Symfony\Component\TypeInfo\TypeResolver\TypeResolver;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse;

abstract class MapApiAutoParts
{

    public function __construct(array $data = [])
    {
        $this->load($data);
    }

    private function load(array $data)
    {
        $typeResolver = TypeResolver::create();
        $input_errors = new InputErrorsAutoPartsWarehouse;

        foreach ($data as $key => $value) {

            if (!empty($value)) {

                $input_errors = new InputErrorsAutoPartsWarehouse;
                $input_errors->propertyExistsEntity(AutoPartsWarehouse::class, $key, 'AutoPartsWarehouse');

                $type = $typeResolver->resolve(new \ReflectionProperty(AutoPartsWarehouse::class, $key))
                    ->getBaseType()
                    ->getTypeIdentifier()
                    ->value;

                if (gettype($value) == 'double' || gettype($value) == 'float') {

                    $value = round($value * 100);
                }

                settype($value, $type);

                if ($type == 'object') {

                    $className = $typeResolver->resolve(new \ReflectionProperty(AutoPartsWarehouse::class, $key))
                        ->getBaseType()
                        ->getClassName();

                    $input_errors->comparingClassNames($className, $value, $key);
                }

                $this->$key = $value;
            }
        }
    }
}
