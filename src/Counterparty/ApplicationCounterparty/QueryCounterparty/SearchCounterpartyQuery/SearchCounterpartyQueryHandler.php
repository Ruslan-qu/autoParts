<?php

namespace App\Counterparty\ApplicationCounterparty\QueryCounterparty\SearchCounterpartyQuery;

use App\Counterparty\ApplicationCounterparty\Errors\InputErrors;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\Counterparty\ApplicationCounterparty\QueryCounterparty\DTOQuery\CounterpartyQuery;
use App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty\CounterpartyRepositoryInterface;


final class SearchCounterpartyQueryHandler
{

    public function __construct(
        private InputErrors $inputErrors,
        private CounterpartyRepositoryInterface $counterpartyRepositoryInterface
    ) {}

    public function handler(CounterpartyQuery $counterpartyQuery): ?array
    {

        $name_counterparty = strtolower(preg_replace(
            '#\s#u',
            '',
            $counterpartyQuery->getNameCounterparty()
        ));
        $this->inputErrors->emptyData($name_counterparty);

        $arr_еntity = $this->counterpartyRepositoryInterface->findOneByCounterparty($name_counterparty);
        $this->inputErrors->emptyEntity($arr_еntity);

        return $arr_еntity;
    }
}
