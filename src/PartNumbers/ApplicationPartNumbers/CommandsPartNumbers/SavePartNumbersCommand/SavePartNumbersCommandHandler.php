<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\SavePartNumbersCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\PartNumbersRepositoryInterface;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartNumbersFromManufacturers;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOPartNumbersCommand\PartNumbersCommand;

final class SavePartNumbersCommandHandler
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

    public function handler(PartNumbersCommand $partNumbersCommand): ?int
    {

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $part_number = strtolower(preg_replace(
            '#\s#',
            '',
            $partNumbersCommand->getPartNumber()
        ));

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
            $partNumbersCommand->getManufacturer()
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

        $additional_descriptions = $partNumbersCommand->getAdditionalDescriptions();
        if (!empty($additional_descriptions)) {
            $input = [
                'additional_descriptions_error' => [
                    'Type' => $additional_descriptions,
                    'Regex' => $additional_descriptions,
                ]
            ];

            $constraint = new Collection([
                'additional_descriptions_error' => new Collection([
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

        $id_part_name = $partNumbersCommand->getIdPartName();

        $id_car_brand = $partNumbersCommand->getIdCarBrand();

        $id_side = $partNumbersCommand->getIdSide();

        $id_body = $partNumbersCommand->getIdBody();

        $id_axle = $partNumbersCommand->getIdAxle();

        $id_in_stock = $partNumbersCommand->getIdInStock();

        $id_original_number = $partNumbersCommand->getIdOriginalNumber();

        if (!empty($data_errors_part_number)) {

            $json_arr_data_errors = json_encode($data_errors_part_number, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }
        /* Валидация дублей */
        $number_doubles = $this->part_numbers_repository_interface
            ->numberDoubles(['part_number' => $part_number]);

        if ($number_doubles != 0) {

            $arr_data_errors = ['Error' => 'Деталь уже существует'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new ConflictHttpException($json_arr_data_errors);
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

        $id = $successfully_save['save'];
        return $id;
    }
}