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

    public function __construct(
        private PartNumbersRepositoryInterface $partNumbersRepositoryInterface,
        private PartNumbersFromManufacturers $partNumbersFromManufacturers
    ) {}

    public function handler(PartNumbersCommand $partNumbersCommand): ?int
    {

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $part_number = strtolower(preg_replace(
            '#\s#',
            '',
            $partNumbersCommand->getPartNumber()
        ));
        $manufacturer = strtolower(preg_replace(
            '#\s#',
            '',
            $partNumbersCommand->getManufacturer()
        ));
        $additional_descriptions = $partNumbersCommand->getAdditionalDescriptions();
        $id_part_name = $partNumbersCommand->getIdPartName();
        $id_car_brand = $partNumbersCommand->getIdCarBrand();
        $id_side = $partNumbersCommand->getIdSide();
        $id_body = $partNumbersCommand->getIdBody();
        $id_axle = $partNumbersCommand->getIdAxle();
        $id_in_stock = $partNumbersCommand->getIdInStock();
        $id_original_number = $partNumbersCommand->getIdOriginalNumber();

        $input = [
            'part_number_error' => [
                'NotBlank' => $part_number,
                'Regex' => $part_number,
            ],
            'manufacturer_error' => [
                'Regex' => $manufacturer
            ],
            'additional_descriptions_error' => [
                'Regex' => $additional_descriptions,
            ]
        ];

        $constraint = new Collection([
            'part_number_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма Номер детали не может быть пустой'
                ),
                'Regex' => new Regex(
                    pattern: '/^[\da-z]*$/i',
                    message: 'Форма Номер детали содержит недопустимые символы'
                )
            ]),
            'manufacturer_error' => new Collection([
                'Regex' => new Regex(
                    pattern: '/^[\da-z]*$/i',
                    message: 'Форма Производитель содержит недопустимые символы'
                )
            ]),
            'additional_descriptions_error' => new Collection([
                'Regex' => new Regex(
                    pattern: '/^[а-яё\w\s]*$/ui',
                    message: 'Форма Описание детали содержит недопустимые символы'
                )
            ])
        ]);

        $errors = $validator->validate($input, $constraint);

        if ($errors->count()) {
            $validator_errors = [];
            foreach ($errors as $key => $value_error) {

                $validator_errors[$key] = [
                    $value_error->getPropertyPath() => $value_error->getMessage()
                ];
            }
            $json_data_errors = json_encode($validator_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_data_errors);
        }

        /* Валидация дублей */
        $number_doubles = $this->partNumbersRepositoryInterface
            ->numberDoubles(['part_number' => $part_number]);

        if ($number_doubles != 0) {

            $arr_data_errors = ['Error' => 'Деталь уже существует'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new ConflictHttpException($json_arr_data_errors);
        }

        $this->partNumbersFromManufacturers->setPartNumber($part_number);
        $this->partNumbersFromManufacturers->setManufacturer($manufacturer);
        $this->partNumbersFromManufacturers->setAdditionalDescriptions($additional_descriptions);
        $this->partNumbersFromManufacturers->setIdPartName($id_part_name);
        $this->partNumbersFromManufacturers->setIdCarBrand($id_car_brand);
        $this->partNumbersFromManufacturers->setIdSide($id_side);
        $this->partNumbersFromManufacturers->setIdBody($id_body);
        $this->partNumbersFromManufacturers->setIdAxle($id_axle);
        $this->partNumbersFromManufacturers->setIdInStock($id_in_stock);
        $this->partNumbersFromManufacturers->setIdOriginalNumber($id_original_number);


        $successfully_save = $this->partNumbersRepositoryInterface->save($this->partNumbersFromManufacturers);

        $id = $successfully_save['save'];
        return $id;
    }
}
