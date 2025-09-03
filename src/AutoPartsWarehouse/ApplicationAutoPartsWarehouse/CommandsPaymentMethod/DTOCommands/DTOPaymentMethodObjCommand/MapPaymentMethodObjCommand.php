<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsPaymentMethod\DTOCommands\DTOPaymentMethodObjCommand;

use Symfony\Component\TypeInfo\Type;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\PaymentMethod;

abstract class MapPaymentMethodObjCommand
{

    public function __construct(array $data = [])
    {
        $this->load($data);
    }

    private function load(array $data)
    {
        foreach ($data as $key => $value) {

            if (!empty($value)) {
                $type = Type::object(PaymentMethod::class);
                $className = $type->getBaseType()->getClassName();
                $input_errors = new InputErrorsAutoPartsWarehouse;
                $input_errors->comparingClassNames($className, $value, $key);
                $this->$key = $value;
            }
        }
    }
}
