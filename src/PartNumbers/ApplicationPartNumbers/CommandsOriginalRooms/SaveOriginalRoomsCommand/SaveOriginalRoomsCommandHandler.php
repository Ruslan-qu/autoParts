<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsOriginalRooms\SaveOriginalRoomsCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\OriginalRooms;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\OriginalRoomsRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsOriginalRooms\DTOCommands\DTOOriginalRoomsCommand\OriginalRoomsCommand;

final class SaveOriginalRoomsCommandHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private OriginalRoomsRepositoryInterface $originalRoomsRepositoryInterface
    ) {}

    public function handler(OriginalRoomsCommand $originalRoomsCommand): ?int
    {

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $original_number = strtolower(preg_replace(
            '#\s#u',
            '',
            $originalRoomsCommand->getOriginalNumber()
        ));

        $original_manufacturer = strtolower($originalRoomsCommand->getOriginalManufacturer());

        $id_participant = $originalRoomsCommand->getIdParticipant();

        $input = [
            'original_number_error' => [
                'NotBlank' => $original_number,
                'Regex' => $original_number,
            ],
            'original_manufacturer_error' => [
                'Regex' => $original_manufacturer
            ]
        ];

        $constraint = new Collection([
            'original_number_error' => new Collection([
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
                    pattern: '/^[\s\da-z]*$/ui',
                    message: 'Форма Производитель содержит недопустимые символы'
                )
            ])
        ]);

        $errors_validate = $validator->validate($input, $constraint);
        $this->inputErrorsPartNumbers->errorValidate($errors_validate);

        /* Валидация дублей */
        $count_duplicate = $this->originalRoomsRepositoryInterface
            ->numberDoubles([
                'original_number' => $original_number,
                'id_participant' => $id_participant
            ]);
        $this->inputErrorsPartNumbers->errorDuplicate($count_duplicate);

        $original_rooms = new OriginalRooms;
        $original_rooms->setOriginalNumber($original_number);
        $original_rooms->setOriginalManufacturer($original_manufacturer);
        $original_rooms->setIdParticipant($id_participant);

        $id = $this->originalRoomsRepositoryInterface->save($original_rooms);

        return $id;
    }
}
