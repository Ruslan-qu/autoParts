<?php

namespace App\Counterparty\ApplicationCounterparty\QueryCounterparty\SearchCounterpartyQuery;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;
use App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty\CounterpartyRepositoryInterface;
use Doctrine\DBAL\Query\QueryBuilder;

final class CreateSearchCounterpartyQueryHandler
{
    private $doctrine;
    private $counterparty_repository_interface;
    private $entity_counterparty;

    public function __construct(
        ManagerRegistry $doctrine,
        CounterpartyRepositoryInterface $counterparty_repository_interface,
        Counterparty $entity_counterparty
    ) {
        $this->counterparty_repository_interface = $counterparty_repository_interface;
        $this->entity_counterparty = $entity_counterparty;
        $this->doctrine = $doctrine;
    }

    public function handler(CreateSearchCounterpartyQuery $createSearchCounterpartyQuery): ?Counterparty
    {

        $name_counterparty = strtolower(preg_replace(
            '#\s#',
            '',
            $createSearchCounterpartyQuery->getNameCounterparty()
        ));

        $counterparty = $this->counterparty_repository_interface->findOneByCounterparty($name_counterparty);

        return $counterparty;
    }
}
