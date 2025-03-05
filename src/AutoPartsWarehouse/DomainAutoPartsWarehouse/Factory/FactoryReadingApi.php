<?php

namespace App\AutoPartsWarehouse\DomainAutoPartsWarehouse\Factory;

use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingApi\DTOCounterpartyAutoParts\ArrCounterparty;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingApi\ReadingApiQuqichbakich\ReadingApiQuqichbakich;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingApi\ReadingApiKazanavtozapchasti\ReadingApiKazanavtozapchasti;

class FactoryReadingApi
{
    public function choiceReadingApi(ArrCounterparty $arrCounterparty, $client)
    {

        $input_errors = new InputErrorsAutoPartsWarehouse;
        $input_errors->emptyData($arrCounterparty);

        foreach ($arrCounterparty->getArrNameCounterparty() as $key => $value) {

            if ($value->getNameCounterparty() == 'kazanavtozapchasti') {

                $readingApiKazanavtozapchasti = new ReadingApiKazanavtozapchasti;
                
                return $readingApiKazanavtozapchasti->reading($client, $value->getNameCounterparty());
            }elseif ($value->getNameCounterparty() == 'quqichbakich') {
    
                $readingApiQuqichbakich = new ReadingApiQuqichbakich;

                return $readingApiQuqichbakich->reading($client, $value->getNameCounterparty());
            }else {

                return NULL;
            }
        }
    }
}
