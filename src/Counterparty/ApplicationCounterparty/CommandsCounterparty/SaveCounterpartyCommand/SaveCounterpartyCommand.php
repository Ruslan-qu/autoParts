<?php

namespace App\Counterparty\ApplicationCounterparty\CommandsCounterparty\SaveCounterpartyCommand;

abstract class SaveCounterpartyCommand
{

    public function __construct(array $data = [])
    {
        $this->loadDataCounterparty($data);
    }

    private function loadDataCounterparty(array $data)
    {

        $this->name_counterparty = $data['form_save_name_counterparty'];
        $this->mail_counterparty = $data['form_save_mail_counterparty'];
        $this->manager_phone = $data['form_save_manager_phone'];
        $this->delivery_phone = $data['form_save_delivery_phone'];
    }
}
