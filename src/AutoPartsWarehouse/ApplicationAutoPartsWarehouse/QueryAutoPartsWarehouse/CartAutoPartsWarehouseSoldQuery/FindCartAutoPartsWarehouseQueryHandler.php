<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\CartAutoPartsWarehouseSoldQuery;

use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\AutoPartsWarehouseRepositoryInterface;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOAutoPartsWarehouseQuery\AutoPartsWarehouseQuery;


final class FindCartAutoPartsWarehouseQueryHandler
{
    public function __construct(
        private AutoPartsWarehouseRepositoryInterface $autoPartsWarehouseRepositoryInterface
    ) {}

    public function handler(AutoPartsWarehouseQuery $autoPartsWarehouseQuery): ?array
    {

        $id = $autoPartsWarehouseQuery->getId();

        if (empty($id)) {
            $arr_data_errors = ['Error' => 'Иди некорректное'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        $find_cart_auto_parts_warehouse = $this->autoPartsWarehouseRepositoryInterface->findCartAutoPartsWarehouse($id);

        if (empty($find_cart_auto_parts_warehouse)) {
            $arr_data_errors = ['Error' => 'Иди некорректное'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $find_cart_auto_parts_warehouse;
    }
}
