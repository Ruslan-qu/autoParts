<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\EditPartNumbersCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\PartNumbersRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOPartNumbersCommand\PartNumbersCommand;

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

        $edit_part_number = strtolower(preg_replace(
            '#\s#',
            '',
            $partNumbersCommand->getPartNumber()
        ));
        $manufacturer = strtolower($partNumbersCommand->getManufacturer());
        $additional_descriptions = $partNumbersCommand->getAdditionalDescriptions();

        $participant = $partNumbersCommand->getIdParticipant();

        $input = [
            'edit_part_number_error' => [
                'NotBlank' => $edit_part_number,
                'Regex' => $edit_part_number,
            ],
            'manufacturer_error' => $manufacturer,
            'additional_descriptions_error' => $additional_descriptions
        ];

        $constraint = new Collection([
            'edit_part_number_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма Номер детали не может быть пустой'
                ),
                'Regex' => new Regex(
                    pattern: '/^[\da-z]*$/i',
                    message: 'Форма Номер детали содержит недопустимые символы'
                )
            ]),
            'manufacturer_error' => new Regex(
                pattern: '/^[\s\da-z]*$/i',
                message: 'Форма Производитель содержит недопустимые символы'
            ),
            'additional_descriptions_error' => new Regex(
                pattern: '/^[а-яё\w\s\da-z]*$/ui',
                message: 'Форма Описание детали содержит недопустимые символы'
            )
        ]);

        $errors_validate = $validator->validate($input, $constraint);
        $this->inputErrorsPartNumbers->errorValidate($errors_validate);

        $id_part_name = $partNumbersCommand->getIdPartName();
        $id_car_brand = $partNumbersCommand->getIdCarBrand();
        $id_side = $partNumbersCommand->getIdSide();
        $id_body = $partNumbersCommand->getIdBody();
        $id_axle = $partNumbersCommand->getIdAxle();
        $id_in_stock = $partNumbersCommand->getIdInStock();
        $id_original_number = $partNumbersCommand->getIdOriginalNumber();

        $id = $partNumbersCommand->getId();
        $this->inputErrorsPartNumbers->emptyData($id);

        $part_number = $this->partNumbersRepositoryInterface->findPartNumbersFromManufacturers($id);
        $this->inputErrorsPartNumbers->emptyEntity($edit_part_number);

        $this->countDuplicate($edit_part_number, $part_number->getPartNumber(), $participant);

        $part_number->setPartNumber($edit_part_number);
        $part_number->setManufacturer($manufacturer);
        $part_number->setAdditionalDescriptions($additional_descriptions);
        $part_number->setIdPartName($id_part_name);
        $part_number->setIdCarBrand($id_car_brand);
        $part_number->setIdSide($id_side);
        $part_number->setIdBody($id_body);
        $part_number->setIdAxle($id_axle);
        $part_number->setIdInStock($id_in_stock);
        $part_number->setIdOriginalNumber($id_original_number);

        $id = $this->partNumbersRepositoryInterface->edit($part_number);

        return $id;
    }

    private function countDuplicate(string $edit_part_number, string $part_number, Participant $participant): static
    {
        if ($edit_part_number != $part_number) {
            /* Валидация дублей */
            $count_duplicate = $this->partNumbersRepositoryInterface
                ->numberDoubles([
                    'part_number' => $edit_part_number,
                    'id_participant' => $participant
                ]);
            $this->inputErrorsPartNumbers->errorDuplicate($count_duplicate);
        }

        return $this;
    }
}
