<?php

namespace App\AutoPartsWarehouse\DomainAutoPartsWarehouse\Factory;

use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingApi\DTOCounterpartyAutoParts\ArrCounterparty;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingEmail\ReadingEmailKazanavtozapchasti\ReadingEmailQuqichbakich;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingEmail\ReadingEmailKazanavtozapchasti\ReadingEmailKazanavtozapchasti;

class FactoryReadingApi
{
    public function choiceReadingApi(ArrCounterparty $arrCounterparty): ?array
    {

        if ($mails_id == false) {

            return null;
        } elseif ($this->addressEmailCounterparty($autoPartsEmail->getEmailImap(), 1) == 'kazan_avtozapchasti@mail.ru') {

            $readingEmailKazanavtozapchasti = new ReadingEmailKazanavtozapchasti;
            return $readingEmailKazanavtozapchasti->reading($autoPartsEmail->getEmailImap(), 1);
        } elseif ($this->addressEmailCounterparty($autoPartsEmail->getEmailImap(), 1) == 'quqichbakich@mail.ru') {

            $readingEmailQuqichbakich = new ReadingEmailQuqichbakich;
            return $readingEmailQuqichbakich->reading($autoPartsEmail->getEmailImap(), 1);
        }
    }

    private function addressEmailCounterparty(Connection $imap, int $value): string
    {

        $headers = imap_headerinfo($imap, $value);

        preg_match_all(
            "/.*?<(.*?)>/",
            $headers->fromaddress,
            $matches,
            PREG_SET_ORDER
        );

        return $matches[0][1];
    }
}
