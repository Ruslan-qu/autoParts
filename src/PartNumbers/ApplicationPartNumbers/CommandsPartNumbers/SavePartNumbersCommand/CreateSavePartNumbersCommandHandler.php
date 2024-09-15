<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\SavePartNumbersCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\CreatePartNumbersCommand;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\PartNumbersRepositoryInterface;
use App\Counterparty\ApplicationCounterparty\CommandsCounterparty\DTOCommands\CreateCounterpartyCommand;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartNumbersFromManufacturers;

final class CreateSavePartNumbersCommandHandler
{
    private $part_numbers_repository_interface;
    private $entity_part_numbers_from_manufacturers;

    public function __construct(
        PartNumbersRepositoryInterface $partNumbersRepositoryInterface,
        PartNumbersFromManufacturers $partNumbersFromManufacturers
    ) {
        $this->part_numbers_repository_interface = $partNumbersRepositoryInterface;
        $this->entity_part_numbers_from_manufacturers = $partNumbersFromManufacturers;
    }

    public function handler(CreatePartNumbersCommand $createPartNumbersCommand): array
    {

        $part_number = strtolower(preg_replace(
            '#\s#',
            '',
            $createPartNumbersCommand->getPartNumber()
        ));

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $input = [
            'name_counterparty_error' => [
                'NotBlank' => $part_number,
                'Type' => $part_number,
                'Regex' => $part_number,
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

        $manufacturer = preg_replace(
            '#\s#',
            '',
            $createPartNumbersCommand->getManufacturer()
        );

        if (!empty($mail_counterparty)) {
            $input = [
                'mail_counterparty_error' => [
                    'Type' => $manufacturer,
                    'Regex' => $manufacturer
                ]
            ];

            $constraint = new Collection([
                'mail_counterparty_error' => new Collection([
                    'Type' => new Type('string'),
                    'Regex' => new Regex(
                        pattern: '/^[\da-z]*$/i',
                        message: 'Форма Поставщик содержит недопустимые символы'
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
                    'Regex' => $manager_phone,
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
                    'Regex' => $delivery_phone,
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
        /* Валидация дублей */
        $number_doubles = $this->counterparty_repository_interface
            ->numberDoubles(['name_counterparty' => $name_counterparty]);

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
                'doubles' => 'Поставщик существует'
            ];
            return $arr_errors_number_doubles;
        }
    }
}
