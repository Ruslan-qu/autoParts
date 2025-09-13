<?php

namespace App\Counterparty\ApplicationCounterparty\QueryCounterparty\SearchCounterpartyQuery;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\Counterparty\ApplicationCounterparty\Errors\InputErrors;
use App\Counterparty\ApplicationCounterparty\QueryCounterparty\DTOQuery\CounterpartyQuery;
use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;
use App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty\CounterpartyRepositoryInterface;


final class SearchEmailCounterpartyQueryHandler
{

    public function __construct(
        private InputErrors $inputErrors,
        private CounterpartyRepositoryInterface $counterpartyRepositoryInterface
    ) {}

    public function handler(CounterpartyQuery $counterpartyQuery): ?Counterparty
    {

        $mail_counterparty = strtolower(preg_replace(
            '#\s#u',
            '',
            $counterpartyQuery->getMailCounterparty()
        ));
        $this->inputErrors->emptyData($mail_counterparty);

        $id_participant = $counterpartyQuery->getIdParticipant();

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();
        $input = [
            'mail_counterparty_error' => [
                'NotBlank' => $mail_counterparty,
                'Email' => $mail_counterparty,
            ]
        ];

        $constraint = new Collection([
            'mail_counterparty_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма Поставщик не может быть пустой'
                ),
                'Email' => new Email(
                    message: 'Email содержит недопустимые символы'
                )
            ])
        ]);

        $errors_validate = $validator->validate($input, $constraint);
        $this->inputErrors->errorValidate($errors_validate);

        $counterparty = $this->counterpartyRepositoryInterface->findOneByEmailCounterparty($mail_counterparty, $id_participant);
        $this->inputErrors->emptyEntity($counterparty);

        return $counterparty;
    }
}
