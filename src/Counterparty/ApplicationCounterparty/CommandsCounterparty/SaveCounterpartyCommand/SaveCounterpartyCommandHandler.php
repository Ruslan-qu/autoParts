<?php

namespace App\Counterparty\ApplicationCounterparty\CommandsCounterparty\SaveCounterpartyCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\Counterparty\ApplicationCounterparty\Errors\InputErrors;
use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;
use App\Counterparty\ApplicationCounterparty\CommandsCounterparty\DTOCommands\CounterpartyCommand;
use App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty\CounterpartyRepositoryInterface;

final class SaveCounterpartyCommandHandler
{

    public function __construct(
        private InputErrors $inputErrors,
        private CounterpartyRepositoryInterface $counterpartyRepositoryInterface,
        private Counterparty $counterparty
    ) {}

    public function handler(CounterpartyCommand $counterpartyCommand): int
    {

        $name_counterparty = strtolower(preg_replace(
            '#\s#',
            '',
            $counterpartyCommand->getNameCounterparty()
        ));

        $mail_counterparty = strtolower(preg_replace(
            '#\s#',
            '',
            $counterpartyCommand->getMailCounterparty()
        ));

        $manager_phone = preg_replace(
            '#\s#',
            '',
            $counterpartyCommand->getManagerPhone()
        );

        $delivery_phone = preg_replace(
            '#\s#',
            '',
            $counterpartyCommand->getDeliveryPhone()
        );

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $input = [
            'name_counterparty_error' => [
                'NotBlank' => $name_counterparty,
                'Regex' => $name_counterparty,
            ],
            'mail_counterparty_error' => [
                'Email' => $mail_counterparty
            ],
            'manager_phone_error' => [
                'Regex' => $manager_phone,
            ],
            'delivery_phone_error' => [
                'Regex' => $delivery_phone,
            ]
        ];

        $constraint = new Collection([
            'name_counterparty_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма Поставщик не может быть пустой'
                ),
                'Regex' => new Regex(
                    pattern: '/^[\da-z]*$/i',
                    message: 'Форма Поставщик содержит недопустимые символы'
                )
            ]),
            'mail_counterparty_error' => new Collection([
                'Email' => new Email(
                    message: 'Форма E-mail содержит недопустимые символы'
                )
            ]),
            'manager_phone_error' => new Collection([
                'Regex' => new Regex(
                    pattern: '/^\+{1}\d{11}$/',
                    message: 'Форма Телефон менеджера содержит:
                        Недопустимые символы
                        или Нет знака +
                        или Неверное количество цифр'
                )
            ]),
            'delivery_phone_error' => new Collection([
                'Regex' => new Regex(
                    pattern: '/^\+{1}\d{11}$/',
                    message: 'Форма Телефон доставки содержит: 
                        Недопустимые символы
                        или Нет знака +
                        или Неверное количество цифр'
                )
            ])
        ]);

        $errors_validate = $validator->validate($input, $constraint);
        $this->inputErrors->errorValidate($errors_validate);

        /* Валидация дублей */
        $count_duplicate = $this->counterpartyRepositoryInterface
            ->numberDoubles(['name_counterparty' => $name_counterparty]);
        $this->inputErrors->errorDuplicate($count_duplicate);

        $this->counterparty->setNameCounterparty($name_counterparty);
        $this->counterparty->setMailCounterparty($mail_counterparty);
        $this->counterparty->setManagerPhone($manager_phone);
        $this->counterparty->setDeliveryPhone($delivery_phone);

        $successfully_save = $this->counterpartyRepositoryInterface->save($this->counterparty);

        $id = $successfully_save['save'];
        return $id;
    }
}
