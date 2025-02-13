<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingEmail\DTOAutoPartsEmail;

use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingEmail\DTOAutoPartsEmail\MapAutoPartsEmail;
use IMAP\Connection;

final class AutoPartsEmail extends MapAutoPartsEmail

{
    protected ?Connection $email_imap = null;

    public function getEmailImap(): ?Connection
    {
        return $this->email_imap;
    }
}
