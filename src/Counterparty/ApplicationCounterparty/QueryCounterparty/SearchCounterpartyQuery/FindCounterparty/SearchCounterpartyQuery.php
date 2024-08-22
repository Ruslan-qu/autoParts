<?php

namespace App\Counterparty\ApplicationCounterparty\QueryCounterparty\SearchCounterpartyQuery\FindCounterparty;

abstract class SearchCounterpartyQuery
{

    public function __construct(array $data = [])
    {
        $this->loadDataCounterparty($data);
    }

    private function loadDataCounterparty(array $data)
    {

        $this->name_counterparty = $data['form_search_name_counterparty'];
        $this->mail_counterparty = $data['form_search_mail_counterparty'];
    }
}
