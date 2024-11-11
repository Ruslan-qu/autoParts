<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\EditPartNumbersCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\PartNumbersRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOPartNumbersCommand\PartNumbersCommand;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOPartNumbersCommand\CreatePartNumbersCommand;

final class EditPartNumbersCommandHandler
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

        $input = [
            'part_number_error' => [
                'NotBlank' => $part_number,
                'Regex' => $part_number,
            ],
            'manufacturer_error' => $manufacturer,
            'additional_descriptions_error' => $additional_descriptions
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
            'manufacturer_error' => new Regex(
                pattern: '/^[\da-z]*$/i',
                message: 'Форма Производитель содержит недопустимые символы'
            ),
            'additional_descriptions_error' => new Regex(
                pattern: '/^[а-яё\w\s]*$/ui',
                message: 'Форма Описание детали содержит недопустимые символы'
            )
        ]);

        $errors_validate = $validator->validate($input, $constraint);
        $this->inputErrorsPartNumbers->errorValidate($errors_validate);

        $arr_edit_part_number['part_number'] = $part_number;

        if (!empty($manufacturer)) {
            $arr_edit_part_number['manufacturer'] = $manufacturer;
        }

        if (!empty($additional_descriptions)) {
            $arr_edit_part_number['additional_descriptions'] = $additional_descriptions;
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

        $id = $partNumbersCommand->getId();
        $arr_edit_part_number['id'] = $id;
        $this->inputErrorsPartNumbers->emptyData($id);

        $edit_part_number = $this->partNumbersRepositoryInterface->findPartNumbersFromManufacturers($id);
        $this->inputErrorsPartNumbers->emptyEntity($edit_part_number);

        if ($part_number != $edit_part_number->getPartNumber()) {
            /* Валидация дублей */
            $count_duplicate = $this->partNumbersRepositoryInterface
                ->numberDoubles(['part_number' => $part_number]);
            $this->inputErrorsPartNumbers->errorDuplicate($count_duplicate);
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

        $id = $successfully_edit['edit'];

        return $id;
    }
}
