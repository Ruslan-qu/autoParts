<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingEmail\EmailProcessing;

use IMAP\Connection;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;

class EmailProcessing
{
    public function processing(): ?array
    {
        $input_errors = new InputErrorsAutoPartsWarehouse;

        $imap = imap_open(
            '{imap.mail.ru:993/imap/ssl/novalidate-cert}INBOX',
            'imap_test_test_test@mail.ru',
            'jVRBymTQUhzvExwcka67'
        );
        $number_unread_emails = imap_search($imap, 'UNSEEN');

        $email_address_counterparty = [];
        if ($number_unread_emails != false) {
            foreach ($number_unread_emails as $key => $value) {
                $email_address_counterparty[$key] = ['mail_counterparty' => $this->addressEmailCounterparty($imap, $value)];
            }
        }
        return $email_address_counterparty;
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
