<?php

namespace App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\Adapters\AdapterPartNumbers;

use App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\Adapters\AdapterPartNumbers\AdapterPartNumbersInterface;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOAutoPartsWarehouseDeleteQuery\AutoPartsWarehouseDeleteQuery;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\AutoPartsWarehouseDelete\AutoPartsWarehouseDeletePartNumbersQueryHandler;

class AdapterPartNumbers implements AdapterPartNumbersInterface
{

    public function __construct(
        private AutoPartsWarehouseDeletePartNumbersQueryHandler $autoPartsWarehouseDeletePartNumbersQueryHandler
    ) {}

    public function autoPartsWarehouseDeletePartNumbers(array $data_part_numbers): void
    {

        $this->autoPartsWarehouseDeletePartNumbersQueryHandler
            ->handler(new AutoPartsWarehouseDeleteQuery($data_part_numbers));
    }
}
