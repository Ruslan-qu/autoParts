<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingApi\ApiProcessing;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\Factory\FactoryReadingApi;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\AutoPartsWarehouseRepositoryInterface;

class ApiProcessing
{
    public function processing(
        ?array $arr_counterparty,
        HttpClientInterface $client,
        AutoPartsWarehouseRepositoryInterface $autoPartsWarehouseRepositoryInterface
    ): ?array {
        $input_errors = new InputErrorsAutoPartsWarehouse;
        $input_errors->emptyData($arr_counterparty);

        $factoryReadingApi = new FactoryReadingApi;

        return $factoryReadingApi->choiceReadingApi(
            $arr_counterparty,
            $client,
            $autoPartsWarehouseRepositoryInterface
        );
    }
}
