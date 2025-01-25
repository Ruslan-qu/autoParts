<?php

namespace App\AutoPartsWarehouse\DomainAutoPartsWarehouse\Factory;

use IMAP\Connection;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingEmail\DTOAutoPartsEmail\AutoPartsEmail;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingEmail\ReadingEmailKazanavtozapchasti\ReadingEmailQuqichbakich;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingEmail\ReadingEmailKazanavtozapchasti\ReadingEmailKazanavtozapchasti;

class FactoryReadingEmail
{
    public function choiceReadingEmail(AutoPartsEmail $autoPartsEmail)
    {

        $input_errors = new InputErrorsAutoPartsWarehouse;
        $input_errors->emptyData($autoPartsEmail->getEmailImap());
        $mails_id = imap_search($autoPartsEmail->getEmailImap(), 'ALL');

        foreach ($mails_id as $key => $value) {

            if ($this->addressEmailCounterparty($autoPartsEmail->getEmailImap(), $value) == 'kazan_avtozapchasti@mail.ru') {

                //$readingEmailKazanavtozapchasti = new ReadingEmailKazanavtozapchasti;
                //return $readingEmailKazanavtozapchasti->reading($autoPartsEmail->getEmailImap(), $value);
            } elseif ($this->addressEmailCounterparty($autoPartsEmail->getEmailImap(), $value) == 'quqichbakich@mail.ru') {

                $readingEmailQuqichbakich = new ReadingEmailQuqichbakich;
                return $readingEmailQuqichbakich->reading($autoPartsEmail->getEmailImap(), $value);
            }
        }


        //$input_errors->determiningFileExtension();
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
