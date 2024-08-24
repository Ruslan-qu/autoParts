<?php

namespace App\Counterparty\ApplicationCounterparty\QueryCounterparty\SearchCounterpartyQuery;

abstract class SearchCounterpartyQuery
{

    public function __construct(array $data = [])
    {
        $this->loadDataCounterparty($data);
    }

    private function loadDataCounterparty(array $data)
    {

        $this->name_counterparty = (string)$data['form_search_name_counterparty'];
    }
}
