<?php

namespace App\Counterparty\ApplicationCounterparty\CommandsSaveEditDeleteCounterparty;

use App\Counterparty\ApplicationCounterparty\CommandsSaveEditDeleteCounterparty\CreateSaveCounterpartyCommand;

abstract class CreateCounterpartyCommand
{

    public function __construct(array $data = [])
    {
        // dd($data['save_counterparty']['form_save_counterparty']);
        $this->loadDataCounterparty($data);
    }

    private function loadDataCounterparty(array $data)
    {
        // dd($data);
        if (isset($data)) {
            $this->counterparty = $data['form_save_counterparty'];
            $this->mail_counterparty = $data['form_save_mail_counterparty'];
        }
    }
}
