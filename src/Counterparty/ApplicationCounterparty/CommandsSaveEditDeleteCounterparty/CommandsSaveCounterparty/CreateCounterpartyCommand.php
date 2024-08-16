<?php

namespace App\Counterparty\ApplicationCounterparty\CommandsSaveEditDeleteCounterparty\CommandsSaveCounterparty;

abstract class CreateCounterpartyCommand
{

    public function __construct(array $data = [])
    {
        $this->loadDataCounterparty($data);
    }

    private function loadDataCounterparty(array $data)
    {

        $this->name_counterparty = $data['form_save_name_counterparty'];
        $this->mail_counterparty = $data['form_save_mail_counterparty'];
    }
}
