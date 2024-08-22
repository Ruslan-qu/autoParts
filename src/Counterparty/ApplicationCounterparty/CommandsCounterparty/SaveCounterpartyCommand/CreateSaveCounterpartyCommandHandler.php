<?php

namespace App\Counterparty\ApplicationCounterparty\CommandsCounterparty\SaveCounterpartyCommand;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;
use App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty\CounterpartyRepositoryInterface;
use App\Counterparty\ApplicationCounterparty\CommandsCounterparty\SaveCounterpartyCommand\CreateSaveCounterpartyCommand;

final class CreateSaveCounterpartyCommandHandler
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

    public function handler(CreateSaveCounterpartyCommand $createSaveCounterpartyCommand): array
    {

        $name_counterparty = strtolower(preg_replace(
            '#\s#',
            '',
            $createSaveCounterpartyCommand->getNameCounterparty()
        ));
        $mail_counterparty = preg_replace(
            '#\s#',
            '',
            $createSaveCounterpartyCommand->getMailCounterparty()
        );

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $input = [
            'name_counterparty_error' => [
                'NotBlank' => $name_counterparty,
                'Type' => $name_counterparty,
                'Regex' => $name_counterparty,
            ],
            'mail_counterparty_error' => [
                'NotBlank' => $mail_counterparty,
                'Type' => $mail_counterparty,
                'Email' => $mail_counterparty,
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
            ]),
            'mail_counterparty_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма E-mail не может быть пустой'
                ),
                'Type' => new Type('string'),
                'Email' => new Email(
                    message: 'Форма E-mail содержит недопустимые символы'
                )
            ])
        ]);

        $data_errors_counterparty = [];
        foreach ($validator->validate($input, $constraint) as $key => $value_error) {

            $data_errors_counterparty[$key] = [
                $value_error->getPropertyPath() => $value_error->getMessage()
            ];
        }

        $manager_phone = preg_replace(
            '#\s#',
            '',
            $createSaveCounterpartyCommand->getManagerPhone()
        );
        if (!empty($manager_phone)) {
            $input = [
                'manager_phone_error' => [
                    'Type' => $manager_phone,
                    'Regex' => $manager_phone,
                ]
            ];

            $constraint = new Collection([
                'manager_phone_error' => new Collection([
                    'Type' => new Type('string'),
                    'Regex' => new Regex(
                        pattern: '/\+{1}\d{11}/',
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
            // dd($data_errors_counterparty_manager_phone);
            $data_errors_counterparty = array_merge($data_errors_counterparty, $data_errors_counterparty_manager_phone);
        }
        $delivery_phone = preg_replace(
            '#\s#',
            '',
            $createSaveCounterpartyCommand->getDeliveryPhone()
        );
        if (!empty($delivery_phone)) {
            $input = [
                'delivery_phone_error' => [
                    'Type' => $delivery_phone,
                    'Regex' => $delivery_phone,
                ]
            ];

            $constraint = new Collection([
                'delivery_phone_error' => new Collection([
                    'Type' => new Type('string'),
                    'Regex' => new Regex(
                        pattern: '/\+{1}\d{11}/',
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
            // dd($data_errors_counterparty_manager_phone);
            $data_errors_counterparty = array_merge($data_errors_counterparty, $data_errors_counterparty_delivery_phone);
        }
        //  dd($data_errors_counterparty);
        if (!empty($data_errors_counterparty)) {

            return $data_errors_counterparty;
        }
        /* Валидация дублей */
        $number_doubles = $this->counterparty_repository_interface
            ->numberDoubles(['name_counterparty' => $name_counterparty, 'mail_counterparty' => $mail_counterparty]);

        if ($number_doubles == 0) {

            $this->entity_counterparty->setNameCounterparty($name_counterparty);
            $this->entity_counterparty->setMailCounterparty($mail_counterparty);
            $this->entity_counterparty->setManagerPhone($manager_phone);
            $this->entity_counterparty->setDeliveryPhone($delivery_phone);

            $successfully_save = $this->counterparty_repository_interface->save($this->entity_counterparty);

            $successfully['successfully'] = $successfully_save;
            return $successfully;
        } else {
            $arr_errors_number_doubles['errors'] = [
                'doubles' => 'Контрагент существует'
            ];
            return $arr_errors_number_doubles;
        }
    }
}
