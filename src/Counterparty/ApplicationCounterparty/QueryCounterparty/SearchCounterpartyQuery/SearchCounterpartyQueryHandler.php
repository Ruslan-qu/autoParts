<?php

namespace App\Counterparty\ApplicationCounterparty\QueryCounterparty\SearchCounterpartyQuery;

use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\Counterparty\ApplicationCounterparty\QueryCounterparty\DTOQuery\CounterpartyQuery;
use App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty\CounterpartyRepositoryInterface;


final class SearchCounterpartyQueryHandler
{

    public function __construct(
        private CounterpartyRepositoryInterface $counterpartyRepositoryInterface
    ) {}

    public function handler(CounterpartyQuery $counterpartyQuery): ?array
    {

        $name_counterparty = strtolower(preg_replace(
            '#\s#',
            '',
            $counterpartyQuery->getNameCounterparty()
        ));

        $counterparty = $this->counterpartyRepositoryInterface->findOneByCounterparty($name_counterparty);

        if (empty($counterparty)) {

            $arr_data_errors = ['Error' => 'Поставщик не найден'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $counterparty;
    }
}
