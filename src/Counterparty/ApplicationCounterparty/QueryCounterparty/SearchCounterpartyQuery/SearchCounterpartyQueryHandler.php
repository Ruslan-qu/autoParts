<?php

namespace App\Counterparty\ApplicationCounterparty\QueryCounterparty\SearchCounterpartyQuery;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\Counterparty\ApplicationCounterparty\Errors\InputErrors;
use App\Counterparty\ApplicationCounterparty\QueryCounterparty\DTOQuery\CounterpartyQuery;
use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;
use App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty\CounterpartyRepositoryInterface;


final class SearchCounterpartyQueryHandler
{

    public function __construct(
        private InputErrors $inputErrors,
        private CounterpartyRepositoryInterface $counterpartyRepositoryInterface
    ) {}

    public function handler(CounterpartyQuery $counterpartyQuery): ?Counterparty
    {

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $name_counterparty = strtolower(preg_replace(
            '#\s#u',
            '',
            $counterpartyQuery->getNameCounterparty()
        ));
        $this->inputErrors->emptyData($name_counterparty);

        $id_participant = $counterpartyQuery->getIdParticipant();

        $input = [
            'name_counterparty_error' => [
                'NotBlank' => $name_counterparty,
                'Regex' => $name_counterparty,
            ]
        ];

        $constraint = new Collection([
            'name_counterparty_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма Поставщик не может быть пустой'
                ),
                'Regex' => new Regex(
                    pattern: '/^[\da-z\.-]*$/i',
                    message: 'Форма Поставщик содержит недопустимые символы'
                )
            ])
        ]);

        $errors_validate = $validator->validate($input, $constraint);
        $this->inputErrors->errorValidate($errors_validate);

        $arr_еntity = $this->counterpartyRepositoryInterface->findOneByCounterparty($name_counterparty, $id_participant);
        $this->inputErrors->emptyEntity($arr_еntity);

        return $arr_еntity;
    }
}
