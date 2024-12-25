<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOPaymentMethodQuery;

use Symfony\Component\TypeInfo\TypeResolver\TypeResolver;

use App\Counterparty\ApplicationCounterparty\Errors\InputErrors;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\PaymentMethod;

abstract class MapPaymentMethodQuery
{

    public function __construct(array $data = [])
    {
        $this->load($data);
    }

    private function load(array $data)
    {
        $typeResolver = TypeResolver::create();
        // dd($data);
        foreach ($data as $key => $value) {

            if (!empty($value)) {

                $input_errors = new InputErrors;
                $input_errors->propertyExistsEntity(PaymentMethod::class, $key, 'PaymentMethod');

                $type = $typeResolver->resolve(new \ReflectionProperty(PaymentMethod::class, $key))
                    ->getBaseType()
                    ->getTypeIdentifier()
                    ->value;
                settype($value, $type);
                $this->$key = $value;
            }
        }
    }
}
