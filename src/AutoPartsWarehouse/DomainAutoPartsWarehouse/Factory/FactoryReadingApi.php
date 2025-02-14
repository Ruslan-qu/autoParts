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
        dd($arrCounterparty[0]);
        $input_errors = new InputErrorsAutoPartsWarehouse;
        $input_errors->emptyData($arrCounterparty);

        foreach ($arrCounterparty as $key => $value) {
            if ($value->getNameCounterparty() == 'kazanavtozapchasti') {

                $readingEmailKazanavtozapchasti = new ReadingApiKazanavtozapchasti;
                return $readingEmailKazanavtozapchasti->reading($autoPartsEmail->getEmailImap(), 1);
            } /*elseif ($value->getNameCounterparty() == 'quqichbakich') {
    
                $readingEmailQuqichbakich = new ReadingEmailQuqichbakich;
                return $readingEmailQuqichbakich->reading($autoPartsEmail->getEmailImap(), 1);
            }*/
        }
    }


    /*private function addressEmailCounterparty(Connection $imap, int $value): string
    {

        $headers = imap_headerinfo($imap, $value);

        preg_match_all(
            "/.*?<(.*?)>/",
            $headers->fromaddress,
            $matches,
            PREG_SET_ORDER
        );

        return $matches[0][1];
    }*/
}
