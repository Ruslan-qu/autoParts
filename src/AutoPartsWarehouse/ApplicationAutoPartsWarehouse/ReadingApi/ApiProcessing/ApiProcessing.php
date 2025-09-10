<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingApi\ApiProcessing;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\Factory\FactoryReadingApi;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingApi\DTOAutoPartsApi\ApiAutoParts;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\AutoPartsWarehouseRepositoryInterface;

class ApiProcessing
{

    public function processing(?array $arr_counterparty)
    {
        $input_errors = new InputErrorsAutoPartsWarehouse;
        $input_errors->emptyData($arr_counterparty);

        $client = new HttpClientInterface;
        $factoryReadingApi = new FactoryReadingApi;
        $autoPartsWarehouseRepositoryInterface = new AutoPartsWarehouseRepositoryInterface;

        return $factoryReadingApi->choiceReadingApi(
            new ApiAutoParts($arr_counterparty),
            $client,
            $autoPartsWarehouseRepositoryInterface
        );
    }
}
