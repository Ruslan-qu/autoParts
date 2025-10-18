<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsPaymentMethod\DTOCommands\DTOPaymentMethodCommand;

use ReflectionProperty;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\PaymentMethod;

abstract class MapPaymentMethodCommand
{

    public function __construct(array $data = [])
    {
        $this->load($data);
    }

    private function load(array $data)
    {

        foreach ($data as $key => $value) {

            if (!empty($value)) {

                $input_errors = new InputErrorsAutoPartsWarehouse;
                $input_errors->propertyExistsEntity(PaymentMethod::class, $key, 'PaymentMethod');

                $refl = new ReflectionProperty(PaymentMethod::class, $key);
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
