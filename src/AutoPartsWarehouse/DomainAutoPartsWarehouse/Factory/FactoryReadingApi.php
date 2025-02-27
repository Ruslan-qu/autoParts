<?php

namespace App\AutoPartsWarehouse\DomainAutoPartsWarehouse\Factory;

use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingApi\DTOCounterpartyAutoParts\ArrCounterparty;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingApi\ReadingApiKazanavtozapchasti\ReadingApiKazanavtozapchasti;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingEmail\ReadingEmailKazanavtozapchasti\ReadingEmailQuqichbakich;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingEmail\ReadingEmailKazanavtozapchasti\ReadingEmailKazanavtozapchasti;

class FactoryReadingApi
{
    public function choiceReadingApi(ArrCounterparty $arrCounterparty): ?array
    {
        dd($arrCounterparty);
        $input_errors = new InputErrorsAutoPartsWarehouse;
        $input_errors->emptyData($arrCounterparty);

        foreach ($arrCounterparty as $key => $value) {
            if ($value->getNameCounterparty() == 'kazanavtozapchasti') {

                $readingApiKazanavtozapchasti = new ReadingApiKazanavtozapchasti;
                return $readingApiKazanavtozapchasti->reading();
            } /*elseif ($value->getNameCounterparty() == 'quqichbakich') {
    
                $readingEmailQuqichbakich = new ReadingEmailQuqichbakich;
                return $readingEmailQuqichbakich->reading($autoPartsEmail->getEmailImap(), 1);
            }*/
        }
    }
}
