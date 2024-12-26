<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DTOCommands\DTOAutoPartsWarehouseCommand;

use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DTOCommands\DTOAutoPartsWarehouseCommand\MapArrAutoPartsWarehouseCommand;

final class ArrAutoPartsWarehouseCommand extends MapArrAutoPartsWarehouseCommand
{
    protected ?array $arr_auto_parts_data = null;

    public function getArrAutoPartsData(): ?array
    {
        return $this->arr_auto_parts_data;
    }
}
