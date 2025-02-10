<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\SavePartNumbersCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\PartNumbersRepositoryInterface;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartNumbersFromManufacturers;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOPartNumbersCommand\PartNumbersCommand;

final class SavePartNumbersCommandHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private PartNumbersRepositoryInterface $partNumbersRepositoryInterface
    ) {}

    public function handler(PartNumbersCommand $partNumbersCommand): ?int
    {

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $part_number = strtolower(preg_replace(
            '#\s#u',
            '',
            $partNumbersCommand->getPartNumber()
        ));

        $manufacturer = strtolower(preg_replace(
            '#\s#u',
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
                    pattern: '/^[\da-z]*$/iu',
                    message: 'Форма Номер детали содержит недопустимые символы'
                )
            ]),
            'manufacturer_error' => new Collection([
                'Regex' => new Regex(
                    pattern: '/^[\da-z]*$/ui',
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

        $errors_validate = $validator->validate($input, $constraint);
        $this->inputErrorsPartNumbers->errorValidate($errors_validate);

        /* Валидация дублей */
        $count_duplicate = $this->partNumbersRepositoryInterface
            ->numberDoubles(['part_number' => $part_number]);
        $this->inputErrorsPartNumbers->errorDuplicate($count_duplicate);

        $partNumbersFromManufacturers = new PartNumbersFromManufacturers;
        $partNumbersFromManufacturers->setPartNumber($part_number);
        $partNumbersFromManufacturers->setManufacturer($manufacturer);
        $partNumbersFromManufacturers->setAdditionalDescriptions($additional_descriptions);
        $partNumbersFromManufacturers->setIdPartName($id_part_name);
        $partNumbersFromManufacturers->setIdCarBrand($id_car_brand);
        $partNumbersFromManufacturers->setIdSide($id_side);
        $partNumbersFromManufacturers->setIdBody($id_body);
        $partNumbersFromManufacturers->setIdAxle($id_axle);
        $partNumbersFromManufacturers->setIdInStock($id_in_stock);
        $partNumbersFromManufacturers->setIdOriginalNumber($id_original_number);


        $successfully_save = $this->partNumbersRepositoryInterface->save($partNumbersFromManufacturers);

        $id = $successfully_save['save'];
        return $id;
    }
}
