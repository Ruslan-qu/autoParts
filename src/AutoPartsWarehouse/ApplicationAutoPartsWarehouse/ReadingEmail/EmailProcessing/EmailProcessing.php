<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingEmail\EmailProcessing;

use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\Factory\FactoryReadingEmail;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingEmail\DTOAutoPartsEmail\AutoPartsEmail;

class EmailProcessing
{
    public static function processing()
    {
        $imap = imap_open(
            '{imap.mail.ru:993/imap/ssl/novalidate-cert}INBOX',
            'imap_test_test_test@mail.ru',
            'jVRBymTQUhzvExwcka67'
        );
        $factoryReadingEmail = new FactoryReadingEmail;

        return $factoryReadingEmail->choiceReadingEmail(new AutoPartsEmail(['email_imap' => $imap]));
    }
}
