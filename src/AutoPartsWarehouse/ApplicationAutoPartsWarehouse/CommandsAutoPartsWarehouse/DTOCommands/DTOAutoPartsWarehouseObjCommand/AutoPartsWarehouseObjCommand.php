<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DTOCommands\DTOAutoPartsWarehouseObjCommand;

use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DTOCommands\DTOAutoPartsWarehouseObjCommand\MapAutoPartsWarehouseObjCommand;

final class AutoPartsWarehouseObjCommand extends MapAutoPartsWarehouseObjCommand
{
    protected ?AutoPartsWarehouse $auto_parts_warehouse = null;

    public function getAutoPartsWarehouse(): ?AutoPartsWarehouse
    {
        return $this->auto_parts_warehouse;
    }
}
