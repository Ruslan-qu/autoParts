<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingApi\DTOCounterpartyAutoParts;

use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingApi\DTOCounterpartyAutoParts\CounterpartyAutoParts;

abstract class MapArrCounterparty
{
    public function __construct(array $data = [])
    {
        $this->load($data);
    }

    private function load(array $data)
    {
        $input_errors = new InputErrorsAutoPartsWarehouse;
        $input_errors->emptyData($data);

        foreach ($data as $key => $value) {

            $arr_counterparty_data[$key] = new CounterpartyAutoParts($value);
        }

        $this->arr_name_counterparty = $arr_counterparty_data;
    }
}
