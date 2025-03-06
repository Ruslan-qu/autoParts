<?php

namespace App\AutoPartsWarehouse\DomainAutoPartsWarehouse\Factory;

use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingApi\DTOCounterpartyAutoParts\ArrCounterparty;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingApi\ReadingApiQuqichbakich\ReadingApiQuqichbakich;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingApi\ReadingApiKazanavtozapchasti\ReadingApiKazanavtozapchasti;

class FactoryReadingApi
{
    public function choiceReadingApi(ArrCounterparty $arrCounterparty, $client, $autoPartsWarehouseRepositoryInterface)
    {

        $input_errors = new InputErrorsAutoPartsWarehouse;
        $input_errors->emptyData($arrCounterparty);

        foreach ($arrCounterparty->getArrNameCounterparty() as $key => $value) {

            if ($value->getNameCounterparty() == 'kazanavtozapchasti') {

                $readingApiKazanavtozapchasti = new ReadingApiKazanavtozapchasti;

                return $readingApiKazanavtozapchasti->reading($client, $value->getNameCounterparty(), $autoPartsWarehouseRepositoryInterface);
            } else {

                return NULL;
            }
        }
    }
}
