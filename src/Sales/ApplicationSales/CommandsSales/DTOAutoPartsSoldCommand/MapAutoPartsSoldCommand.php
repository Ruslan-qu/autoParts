<?php

namespace App\Sales\ApplicationSales\CommandsSales\DTOAutoPartsSoldCommand;

use App\Sales\DomainSales\DomainModelSales\AutoPartsSold;
use Symfony\Component\TypeInfo\TypeResolver\TypeResolver;
use App\Sales\ApplicationSales\ErrorsSales\InputErrorsSales;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

abstract class MapAutoPartsSoldCommand
{

    public function __construct(array $data = [])
    {
        $this->load($data);
    }

    private function load(array $data)
    {
        $typeResolver = TypeResolver::create();
        $input_errors = new InputErrorsSales;

        foreach ($data as $key => $value) {

            $input_errors->propertyExistsEntity(AutoPartsSold::class, $key, 'AutoPartsSold');

            if (!empty($value)) {

                $type = $typeResolver->resolve(new \ReflectionProperty(AutoPartsSold::class, $key))
                    ->getBaseType()
                    ->getTypeIdentifier()
                    ->value;

                if (gettype($value) == 'double' || gettype($value) == 'float') {

                    $value = round($value * 100);
                }

                settype($value, $type);

                if ($type == 'object') {

                    $className = $typeResolver->resolve(new \ReflectionProperty(AutoPartsSold::class, $key))
                        ->getBaseType()
                        ->getClassName();

                    $input_errors->comparingClassNames($className, $value, $key);
                }

                $this->$key = $value;
            }
        }
    }
}
