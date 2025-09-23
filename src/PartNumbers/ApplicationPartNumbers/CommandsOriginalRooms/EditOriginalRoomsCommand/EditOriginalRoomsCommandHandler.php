<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsOriginalRooms\EditOriginalRoomsCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\OriginalRoomsRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsOriginalRooms\DTOCommands\DTOOriginalRoomsCommand\OriginalRoomsCommand;

final class EditOriginalRoomsCommandHandler
{
    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private OriginalRoomsRepositoryInterface $originalRoomsRepositoryInterface
    ) {}

    public function handler(OriginalRoomsCommand $originalRoomsCommand): ?int
    {


        $edit_original_number = strtolower(preg_replace(
            '#\s#u',
            '',
            $originalRoomsCommand->getOriginalNumber()
        ));

        $original_manufacturer = strtolower(preg_replace(
            '#\s#u',
            '',
            $originalRoomsCommand->getOriginalManufacturer()
        ));

        $participant = $originalRoomsCommand->getIdParticipant();

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();
        $input = [
            'edit_original_number_error' => [
                'NotBlank' => $edit_original_number,
                'Regex' => $edit_original_number,
            ],
            'original_manufacturer_error' => [
                'Regex' => $original_manufacturer
            ]
        ];

        $constraint = new Collection([
            'edit_original_number_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма оригинальный номер не может быть пустой'
                ),
                'Regex' => new Regex(
                    pattern: '/^[\da-z]*$/ui',
                    message: 'Форма оригинальный номер содержит недопустимые символы'
                )
            ]),
            'original_manufacturer_error' => new Collection([
                'Regex' => new Regex(
                    pattern: '/^[\da-z]*$/ui',
                    message: 'Форма Производитель содержит недопустимые символы'
                )
            ])
        ]);


        $errors_validate = $validator->validate($input, $constraint);
        $this->inputErrorsPartNumbers->errorValidate($errors_validate);


        $id = $originalRoomsCommand->getId();
        $this->inputErrorsPartNumbers->emptyData($id);

        $original_rooms = $this->originalRoomsRepositoryInterface->findOriginalRooms($id);
        $this->inputErrorsPartNumbers->emptyEntity($original_rooms);

        $this->countDuplicate($edit_original_number, $original_rooms->getOriginalNumber(), $participant);

        $original_rooms->setOriginalNumber($edit_original_number);
        $original_rooms->setOriginalManufacturer($original_manufacturer);

        $id = $this->originalRoomsRepositoryInterface->edit($original_rooms);

        return $id;
    }

    private function countDuplicate(string $edit_original_number, string $original_number, Participant $participant): static
    {
        if ($edit_original_number != $original_number) {
            /* Валидация дублей */
            $count_duplicate = $this->originalRoomsRepositoryInterface
                ->numberDoubles([
                    'original_number' => $edit_original_number,
                    'id_participant' => $participant
                ]);
            $this->inputErrorsPartNumbers->errorDuplicate($count_duplicate);
        }

        return $this;
    }
}
