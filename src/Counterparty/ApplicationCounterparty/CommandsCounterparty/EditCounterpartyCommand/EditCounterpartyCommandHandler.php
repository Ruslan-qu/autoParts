<?php

namespace App\Counterparty\ApplicationCounterparty\CommandsCounterparty\EditCounterpartyCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\Counterparty\ApplicationCounterparty\CommandsCounterparty\DTOCommands\CounterpartyCommand;
use App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty\CounterpartyRepositoryInterface;

final class EditCounterpartyCommandHandler
{
    private $counterparty_repository_interface;

    public function __construct(
        CounterpartyRepositoryInterface $counterpartyRepositoryInterface
    ) {
        $this->counterparty_repository_interface = $counterpartyRepositoryInterface;
    }

    public function handler(CounterpartyCommand $counterpartyCommand): array
    {



        $name_counterparty = strtolower(preg_replace(
            '#\s#',
            '',
            $counterpartyCommand->getNameCounterparty()
        ));

        $mail_counterparty = preg_replace(
            '#\s#',
            '',
            $counterpartyCommand->getMailCounterparty()
        );

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

        $errors = $validator->validate($input, $constraint);

        if ($errors->count()) {
            $validator_errors = [];
            foreach ($validator->validate($input, $constraint) as $key => $value_error) {

                $validator_errors[$key] = [
                    $value_error->getPropertyPath() => $value_error->getMessage()
                ];
            }
            $json_data_errors = json_encode($validator_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_data_errors);
        }

        $id = $counterpartyCommand->getId();

        if (empty($id)) {

            $arr_data_errors = ['Error' => 'Иди некорректное'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        $edit_counterparty = $this->counterparty_repository_interface->findCounterparty($id);

        $edit_counterparty->setNameCounterparty($name_counterparty);
        $edit_counterparty->setMailCounterparty($mail_counterparty);
        $edit_counterparty->setManagerPhone($manager_phone);
        $edit_counterparty->setDeliveryPhone($delivery_phone);

        $successfully_edit = $this->counterparty_repository_interface->edit($edit_counterparty);

        return $successfully_edit;
    }
}
