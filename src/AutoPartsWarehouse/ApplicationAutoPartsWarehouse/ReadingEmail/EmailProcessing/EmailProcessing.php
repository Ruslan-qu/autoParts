<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingEmail\EmailProcessing;

use stdClass;
use IMAP\Connection;

class EmailProcessing
{
    public function emailCounterparty(): ?array
    {

        $imap = $this->imapMail();

        $number_unread_emails = $this->numberUnreadEmails($imap, 'UNSEEN');

        $email_address_counterparty = [];
        foreach ($number_unread_emails as $key => $value) {
            $headers = $this->numberUnreadEmails($imap, $value);
            $email_address_counterparty[$key] = ['mail_counterparty' => $this->addressEmailCounterparty($headers)];
        }

        return $email_address_counterparty;
    }

    private function imapMail(): Connection
    {
        $imap = imap_open(
            '{imap.mail.ru:993/imap/ssl/novalidate-cert}INBOX',
            'imap_test_test_test@mail.ru',
            'jVRBymTQUhzvExwcka67'
        );

        return $imap;
    }

    private function numberUnreadEmails(Connection $imap, string $criteria): ?array
    {
        $number_unread_emails = imap_search($imap, $criteria);

        return $number_unread_emails;
    }

    private function headers(Connection $imap, int $num): stdClass|false
    {
        $headers = imap_headerinfo($imap, $num);

        return $headers;
    }

    private function addressEmailCounterparty($headers): string
    {

        preg_match_all(
            "/.*?<(.*?)>/",
            $headers->fromaddress,
            $matches,
            PREG_SET_ORDER
        );

        return $matches[0][1];
    }
}
