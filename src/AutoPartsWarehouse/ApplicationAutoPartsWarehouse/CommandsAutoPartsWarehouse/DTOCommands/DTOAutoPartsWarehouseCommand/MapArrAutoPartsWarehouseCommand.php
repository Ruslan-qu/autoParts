<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DTOCommands\DTOAutoPartsWarehouseCommand;

use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DTOCommands\DTOAutoPartsWarehouseCommand\AutoPartsWarehouseCommand;

abstract class MapArrAutoPartsWarehouseCommand
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
            $arr_auto_parts_data[$key] = ['auto_parts_data' => new AutoPartsWarehouseCommand($value)];
        }
        $this->arr_auto_parts_data = (array)$arr_auto_parts_data;
    }
}
