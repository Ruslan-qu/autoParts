<?php

namespace App\AutoPartsWarehouse\DomainAutoPartsWarehouse\Factory;

use IMAP\Connection;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingEmail\DTOAutoPartsEmail\AutoPartsEmail;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingEmail\ReadingEmailQuqichbakich\ReadingEmailQuqichbakich;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingEmail\ReadingEmailKazanavtozapchasti\ReadingEmailKazanavtozapchasti;

class FactoryReadingEmail
{
    public function choiceReadingEmail(AutoPartsEmail $autoPartsEmail): ?array
    {
        $input_errors = new InputErrorsAutoPartsWarehouse;

        $emails_id = imap_search($autoPartsEmail->getEmailImap(), 'UNSEEN');
        //$headers = imap_headerinfo($autoPartsEmail->getEmailImap(), 1);
        dd($emails_id);
        $input_errors->emptyData($emails_id);
        $arr_email = [];
        if ($this->addressEmailCounterparty($autoPartsEmail->getEmailImap(), 1) == 'kazan_avtozapchasti@mail.ru') {

            $readingEmailKazanavtozapchasti = new ReadingEmailKazanavtozapchasti;
            $arr_email = $readingEmailKazanavtozapchasti->reading($autoPartsEmail->getEmailImap(), 1);
        } elseif ($this->addressEmailCounterparty($autoPartsEmail->getEmailImap(), 1) == 'quqichbakich@mail.ru') {

            $readingEmailQuqichbakich = new ReadingEmailQuqichbakich;
            $arr_email = $readingEmailQuqichbakich->reading($autoPartsEmail->getEmailImap(), 1);
        } else {
            $arr_email = null;
        }

        return $arr_email;
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
