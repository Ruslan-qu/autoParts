<?php

namespace App\Counterparty\ApplicationCounterparty\CommandsCounterparty\EditCounterpartyCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\Counterparty\ApplicationCounterparty\CommandsCounterparty\DTOCommands\CreateCounterpartyCommand;
use App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty\CounterpartyRepositoryInterface;

final class CreateEditCounterpartyCommandHandler
{
    private $counterparty_repository_interface;

    public function __construct(
        CounterpartyRepositoryInterface $counterpartyRepositoryInterface
    ) {
        $this->counterparty_repository_interface = $counterpartyRepositoryInterface;
    }

    public function handler(CreateCounterpartyCommand $createCounterpartyCommand): array
    {



        $name_counterparty = strtolower(preg_replace(
            '#\s#',
            '',
            $createCounterpartyCommand->getNameCounterparty()
        ));

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $input = [
            'name_counterparty_error' => [
                'NotBlank' => $name_counterparty,
                'Type' => $name_counterparty,
                'Regex' => $name_counterparty,
            ]
        ];

        $constraint = new Collection([
            'name_counterparty_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма Поставщик не может быть пустой'
                ),
                'Type' => new Type('string'),
                'Regex' => new Regex(
                    pattern: '/^[\da-z]*$/i',
                    message: 'Форма Поставщик содержит недопустимые символы'
                )
            ])
        ]);

        $data_errors_counterparty = [];
        foreach ($validator->validate($input, $constraint) as $key => $value_error) {

            $data_errors_counterparty[$key] = [
                $value_error->getPropertyPath() => $value_error->getMessage()
            ];
        }

        $mail_counterparty = preg_replace(
            '#\s#',
            '',
            $createCounterpartyCommand->getMailCounterparty()
        );

        if (!empty($mail_counterparty)) {
            $input = [
                'mail_counterparty_error' => [
                    'Type' => $mail_counterparty,
                    'Email' => $mail_counterparty
                ]
            ];

            $constraint = new Collection([
                'mail_counterparty_error' => new Collection([
                    'Type' => new Type('string'),
                    'Email' => new Email(
                        message: 'Форма E-mail содержит недопустимые символы'
                    )
                ])
            ]);
            $data_errors_counterparty_mail = [];
            foreach ($validator->validate($input, $constraint) as $key => $value_error) {

                $data_errors_counterparty_mail[$key] = [
                    $value_error->getPropertyPath() => $value_error->getMessage()
                ];
            }

            $data_errors_counterparty = array_merge($data_errors_counterparty, $data_errors_counterparty_mail);
        }

        $manager_phone = preg_replace(
            '#\s#',
            '',
            $createCounterpartyCommand->getManagerPhone()
        );
        if (!empty($manager_phone)) {
            $input = [
                'manager_phone_error' => [
                    'Type' => $manager_phone,
                    'Regex' => $manager_phone
                ]
            ];

            $constraint = new Collection([
                'manager_phone_error' => new Collection([
                    'Type' => new Type('string'),
                    'Regex' => new Regex(
                        pattern: '/^\+{1}\d{11}$/',
                        message: 'Форма Телефон менеджера содержит:
                        1) Недопустимые символы
                        2) Нет знака +
                        3) Неверное количество цифр'
                    )
                ])
            ]);
            $data_errors_counterparty_manager_phone = [];
            foreach ($validator->validate($input, $constraint) as $key => $value_error) {

                $data_errors_counterparty_manager_phone[$key] = [
                    $value_error->getPropertyPath() => $value_error->getMessage()
                ];
            }

            $data_errors_counterparty = array_merge($data_errors_counterparty, $data_errors_counterparty_manager_phone);
        }
        $delivery_phone = preg_replace(
            '#\s#',
            '',
            $createCounterpartyCommand->getDeliveryPhone()
        );
        if (!empty($delivery_phone)) {
            $input = [
                'delivery_phone_error' => [
                    'Type' => $delivery_phone,
                    'Regex' => $delivery_phone
                ]
            ];

            $constraint = new Collection([
                'delivery_phone_error' => new Collection([
                    'Type' => new Type('string'),
                    'Regex' => new Regex(
                        pattern: '/^\+{1}\d{11}$/',
                        message: 'Форма Телефон доставки содержит: 
                        1) Недопустимые символы
                        2) Нет знака +
                        3) Неверное количество цифр'
                    )
                ])
            ]);

            $data_errors_counterparty_delivery_phone = [];
            foreach ($validator->validate($input, $constraint) as $key => $value_error) {

                $data_errors_counterparty_delivery_phone[$key] = [
                    $value_error->getPropertyPath() => $value_error->getMessage()
                ];
            }

            $data_errors_counterparty = array_merge($data_errors_counterparty, $data_errors_counterparty_delivery_phone);
        }

        if (!empty($data_errors_counterparty)) {

            return $data_errors_counterparty;
        }

        $id = $createCounterpartyCommand->getId();

        if (empty($id)) {
            $arr_errors_id['errors'] = [
                'doubles' => 'Поставщик не существует'
            ];

            return $arr_errors_id;
        }

        $edit_counterparty = $this->counterparty_repository_interface->findCounterparty($id);

        $edit_counterparty->setNameCounterparty($name_counterparty);
        $edit_counterparty->setMailCounterparty($mail_counterparty);
        $edit_counterparty->setManagerPhone($manager_phone);
        $edit_counterparty->setDeliveryPhone($delivery_phone);

        $successfully_edit = $this->counterparty_repository_interface->edit();

        $successfully['successfully'] = $successfully_edit;

        return $successfully;
    }
}
