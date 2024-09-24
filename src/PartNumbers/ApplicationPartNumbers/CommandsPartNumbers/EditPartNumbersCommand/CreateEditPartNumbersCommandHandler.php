<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\EditPartNumbersCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\PartNumbers\InfrastructurePartNumbers\RepositoryPartNumbers\PartNumbersFromManufacturersRepository;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOPartNumbersCommand\CreatePartNumbersCommand;

final class CreateEditPartNumbersCommandHandler
{
    private $part_numbers_from_manufacturers_repository;

    public function __construct(
        PartNumbersFromManufacturersRepository $partNumbersFromManufacturersRepository
    ) {
        $this->part_numbers_from_manufacturers_repository = $partNumbersFromManufacturersRepository;
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

        $id_part_name = $createPartNumbersCommand->getIdPartName();

        $id_car_brand = $createPartNumbersCommand->getIdCarBrand();

        $id_side = $createPartNumbersCommand->getIdSide();

        $id_body = $createPartNumbersCommand->getIdBody();

        $id_axle = $createPartNumbersCommand->getIdAxle();

        $id_in_stock = $createPartNumbersCommand->getIdInStock();

        $id_original_number = $createPartNumbersCommand->getIdOriginalNumber();

        if (!empty($data_errors_part_number)) {

            $json_arr_data_errors = json_encode($data_errors_part_number, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        $id = $createPartNumbersCommand->getId();

        if (empty($id)) {

            return null;
        }

        $edit_part_number = $this->part_numbers_from_manufacturers_repository->findPartNumbersFromManufacturers($id);

        if (empty($edit_part_number)) {

            return null;
        }

        $edit_part_number->setPartNumber($part_number);
        $edit_part_number->setManufacturer($manufacturer);
        $edit_part_number->setAdditionalDescriptions($additional_descriptions);
        $edit_part_number->setIdPartName($id_part_name);
        $edit_part_number->setIdCarBrand($id_car_brand);
        $edit_part_number->setIdSide($id_side);
        $edit_part_number->setIdBody($id_body);
        $edit_part_number->getIdAxle($id_axle);
        $edit_part_number->setIdInStock($id_in_stock);
        $edit_part_number->setIdOriginalNumber($id_original_number);

        $successfully_edit = $this->part_numbers_from_manufacturers_repository->edit($edit_part_number);

        $successfully['successfully'] = $successfully_edit;

        return $successfully;
    }
}
