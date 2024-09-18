<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\SavePartNumbersCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\PartNumbersRepositoryInterface;
use App\Counterparty\ApplicationCounterparty\CommandsCounterparty\DTOCommands\CreateCounterpartyCommand;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartNumbersFromManufacturers;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOPartNumbersCommand\CreatePartNumbersCommand;

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
        dd($createPartNumbersCommand);
        $part_number = strtolower(preg_replace(
            '#\s#',
            '',
            $createPartNumbersCommand->getPartNumber()
        ));

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $input = [
            'part_number_error' => [
                'NotBlank' => $part_number,
                'Type' => $part_number,
                'Regex' => $part_number,
            ]
        ];

        $constraint = new Collection([
            'part_number_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма Номер детали не может быть пустой'
                ),
                'Type' => new Type('string'),
                'Regex' => new Regex(
                    pattern: '/^[\da-z]*$/i',
                    message: 'Форма Номер детали содержит недопустимые символы'
                )
            ])
        ]);

        $data_errors_part_number = [];
        foreach ($validator->validate($input, $constraint) as $key => $value_error) {

            $data_errors_part_number[$key] = [
                $value_error->getPropertyPath() => $value_error->getMessage()
            ];
        }

        $manufacturer = strtolower(preg_replace(
            '#\s#',
            '',
            $createPartNumbersCommand->getManufacturer()
        ));

        if (!empty($manufacturer)) {
            $input = [
                'manufacturer_error' => [
                    'Type' => $manufacturer,
                    'Regex' => $manufacturer
                ]
            ];

            $constraint = new Collection([
                'manufacturer_error' => new Collection([
                    'Type' => new Type('string'),
                    'Regex' => new Regex(
                        pattern: '/^[\da-z]*$/i',
                        message: 'Форма Производитель содержит недопустимые символы'
                    )
                ])
            ]);
            $data_errors_manufacturer = [];
            foreach ($validator->validate($input, $constraint) as $key => $value_error) {

                $data_errors_manufacturer[$key] = [
                    $value_error->getPropertyPath() => $value_error->getMessage()
                ];
            }

            $data_errors_part_number = array_merge($data_errors_part_number, $data_errors_manufacturer);
        }

        $additional_descriptions = $createPartNumbersCommand->getAdditionalDescriptions();
        if (!empty($additional_descriptions)) {
            $input = [
                'manager_phone_error' => [
                    'Type' => $additional_descriptions,
                    'Regex' => $additional_descriptions,
                ]
            ];

            $constraint = new Collection([
                'manager_phone_error' => new Collection([
                    'Type' => new Type('string'),
                    'Regex' => new Regex(
                        pattern: '/^[а-яё\w\s]*$/ui',
                        message: 'Форма Описание детали содержит недопустимые символы'
                    )
                ])
            ]);
            $data_errors_additional_descriptions = [];
            foreach ($validator->validate($input, $constraint) as $key => $value_error) {

                $data_errors_additional_descriptions[$key] = [
                    $value_error->getPropertyPath() => $value_error->getMessage()
                ];
            }

            $data_errors_part_number = array_merge($data_errors_part_number, $data_errors_additional_descriptions);
        }

        $id_part_name = $createPartNumbersCommand->getIdPartName();
        if (!empty($id_part_name)) {
        }
        $id_car_brand = $createPartNumbersCommand->getIdCarBrand();
        if (!empty($id_car_brand)) {
        }
        $id_side = $createPartNumbersCommand->getIdSide();
        if (!empty($id_side)) {
        }
        $id_body = $createPartNumbersCommand->getIdBody();
        if (!empty($id_body)) {
        }
        $id_axle = $createPartNumbersCommand->getIdAxle();
        if (!empty($id_axle)) {
        }
        $id_in_stock = $createPartNumbersCommand->getIdInStock();
        if (!empty($id_in_stock)) {
        }
        $id_original_number = $createPartNumbersCommand->getIdOriginalNumber();
        if (!empty($id_original_number)) {
        }

        if (!empty($data_errors_counterparty)) {

            return $data_errors_counterparty;
        }
        /* Валидация дублей */
        $number_doubles = $this->part_numbers_repository_interface
            ->numberDoubles(['part_number' => $part_number]);

        if ($number_doubles != 0) {

            $arr_errors_number_doubles['errors'] = [
                'doubles' => 'Поставщик существует'
            ];

            return $arr_errors_number_doubles;
        }


        $this->entity_part_numbers_from_manufacturers->setPartNumber($part_number);
        $this->entity_part_numbers_from_manufacturers->setManufacturer($manufacturer);
        $this->entity_part_numbers_from_manufacturers->setAdditionalDescriptions($additional_descriptions);
        $this->entity_part_numbers_from_manufacturers->setIdPartName($id_part_name);
        $this->entity_part_numbers_from_manufacturers->setIdCarBrand($id_car_brand);
        $this->entity_part_numbers_from_manufacturers->setIdSide($id_side);
        $this->entity_part_numbers_from_manufacturers->setIdBody($id_body);
        $this->entity_part_numbers_from_manufacturers->setIdAxle($id_axle);
        $this->entity_part_numbers_from_manufacturers->setIdInStock($id_in_stock);
        $this->entity_part_numbers_from_manufacturers->setIdOriginalNumber($id_original_number);


        $successfully_save = $this->part_numbers_repository_interface->save($this->entity_part_numbers_from_manufacturers);

        $successfully['successfully'] = $successfully_save;
        return $successfully;
    }
}
