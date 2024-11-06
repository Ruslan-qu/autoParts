<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\AutoPartsWarehouseDeleteCounterparty;

use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\AutoPartsWarehouseRepositoryInterface;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryAutoPartsWarehouse\DTOQuery\DTOAutoPartsWarehouseDeleteCounterpartyQuery\AutoPartsWarehouseDeleteCounterpartyQuery;


final class AutoPartsWarehouseDeleteCounterpartyQueryHandler
{
    public function __construct(
        private AutoPartsWarehouseRepositoryInterface $autoPartsWarehouseRepositoryInterface
    ) {}

    public function handler(AutoPartsWarehouseDeleteCounterpartyQuery $autoPartsWarehouseDeleteCounterpartyQuery): void
    {

        $id_counterparty = $autoPartsWarehouseDeleteCounterpartyQuery->getIdCounterparty();

        if (empty($id_counterparty)) {
            $arr_data_errors = ['Error' => 'Иди некорректное'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        $delete_part_numbers_warehouse = $this->autoPartsWarehouseRepositoryInterface
            ->findByAutoPartsWarehouseDeleteCounterparty($id_counterparty);

        if (!empty($delete_part_numbers_warehouse)) {

            $arr_data_errors = ['Error' => 'Поставщик указан на складе автопчастей, перед удалением, измените поставщика на складе'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }
    }
}
