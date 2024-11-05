<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\EditPartNumbersCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\PartNumbersRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOPartNumbersCommand\PartNumbersCommand;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOPartNumbersCommand\CreatePartNumbersCommand;

final class EditPartNumbersCommandHandler
{
    public function __construct(
        private PartNumbersRepositoryInterface $partNumbersRepositoryInterface
    ) {}

    public function handler(PartNumbersCommand $partNumbersCommand): ?array
    {



        $part_number = strtolower(preg_replace(
            '#\s#',
            '',
            $partNumbersCommand->getPartNumber()
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
            $partNumbersCommand->getManufacturer()
        ));
        $arr_edit_part_number['part_number'] = $part_number;

        if (!empty($manufacturer)) {
            $arr_edit_part_number['manufacturer'] = $manufacturer;

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
            $arr_edit_part_number['additional_descriptions'] = $additional_descriptions;

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
        if (!empty($id_part_name)) {
            $arr_edit_part_number['id_part_name'] = $id_part_name;
        }

        $id_car_brand = $partNumbersCommand->getIdCarBrand();
        if (!empty($id_car_brand)) {
            $arr_edit_part_number['id_car_brand'] = $id_car_brand;
        }

        $id_side = $partNumbersCommand->getIdSide();
        if (!empty($id_side)) {
            $arr_edit_part_number['id_side'] = $id_side;
        }

        $id_body = $partNumbersCommand->getIdBody();
        if (!empty($id_body)) {
            $arr_edit_part_number['id_body'] = $id_body;
        }

        $id_axle = $partNumbersCommand->getIdAxle();
        if (!empty($id_axle)) {
            $arr_edit_part_number['id_axle'] = $id_axle;
        }

        $id_in_stock = $partNumbersCommand->getIdInStock();
        if (!empty($id_in_stock)) {
            $arr_edit_part_number['id_in_stock'] = $id_in_stock;
        }

        $id_original_number = $partNumbersCommand->getIdOriginalNumber();
        if (!empty($id_part_name)) {
            $arr_edit_part_number['id_original_number'] = $id_original_number;
        }

        if (!empty($data_errors_part_number)) {

            $json_arr_data_errors = json_encode($data_errors_part_number, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        $id = $partNumbersCommand->getId();
        $arr_edit_part_number['id'] = $id;

        if (empty($id)) {

            $arr_data_errors = ['Error' => 'Иди некорректное'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        $edit_part_number = $this->partNumbersRepositoryInterface->findPartNumbersFromManufacturers($id);

        if (empty($edit_part_number)) {

            $arr_data_errors = ['Error' => 'Иди некорректное'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        if ($part_number != $edit_part_number->getPartNumber()) {
            /* Валидация дублей */
            $number_doubles = $this->partNumbersRepositoryInterface
                ->numberDoubles(['part_number' => $part_number]);

            if ($number_doubles != 0) {

                return null;
            }
        }

        $edit_part_number->setPartNumber($part_number);
        $edit_part_number->setManufacturer($manufacturer);
        $edit_part_number->setAdditionalDescriptions($additional_descriptions);
        $edit_part_number->setIdPartName($id_part_name);
        $edit_part_number->setIdCarBrand($id_car_brand);
        $edit_part_number->setIdSide($id_side);
        $edit_part_number->setIdBody($id_body);
        $edit_part_number->setIdAxle($id_axle);
        $edit_part_number->setIdInStock($id_in_stock);
        $edit_part_number->setIdOriginalNumber($id_original_number);

        $successfully_edit = $this->partNumbersRepositoryInterface->edit($arr_edit_part_number);

        $successfully['successfully'] = $successfully_edit;

        return $successfully;
    }
}
