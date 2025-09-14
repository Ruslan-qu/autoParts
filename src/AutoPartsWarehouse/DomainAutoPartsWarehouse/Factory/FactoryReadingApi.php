<?php

namespace App\AutoPartsWarehouse\DomainAutoPartsWarehouse\Factory;

use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingApi\ReadingApiKazanavtozapchasti\ReadingApiKazanavtozapchasti;

class FactoryReadingApi
{
    public function choiceReadingApi($arr_counterparty, $client, $autoPartsWarehouseRepositoryInterface)
    {

        $input_errors = new InputErrorsAutoPartsWarehouse;
        $input_errors->emptyData($arr_counterparty);

        foreach ($arr_counterparty as $key => $value) {

            if ($value->getNameCounterparty() == 'kazanavtozapchasti') {

                $readingApiKazanavtozapchasti = new ReadingApiKazanavtozapchasti;

                return $readingApiKazanavtozapchasti->reading(
                    $client,
                    $value->getNameCounterparty(),
                    $autoPartsWarehouseRepositoryInterface
                );
            } else {

                return NULL;
            }
        }
    }
}
