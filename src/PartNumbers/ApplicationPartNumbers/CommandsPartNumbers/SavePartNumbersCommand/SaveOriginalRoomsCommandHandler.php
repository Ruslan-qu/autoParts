<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\SavePartNumbersCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\OriginalRooms;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\OriginalRoomsRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOOriginalRoomsCommand\OriginalRoomsCommand;

final class SaveOriginalRoomsCommandHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private OriginalRoomsRepositoryInterface $originalRoomsRepositoryInterface,
        private OriginalRooms $originalRooms
    ) {}

    public function handler(OriginalRoomsCommand $originalRoomsCommand): ?array
    {

        $original_number = strtolower(preg_replace(
            '#\s#',
            '',
            $originalRoomsCommand->getOriginalNumber()
        ));

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $input = [
            'original_number_error' => [
                'NotBlank' => $original_number,
                'Type' => $original_number,
                'Regex' => $original_number,
            ]
        ];

        $constraint = new Collection([
            'original_number_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма Оригинальный номер не может быть пустой'
                ),
                'Type' => new Type('string'),
                'Regex' => new Regex(
                    pattern: '/^[\da-z]*$/i',
                    message: 'Форма Оригинальный номер содержит недопустимые символы'
                )
            ])
        ]);

        $errors_validate = $validator->validate($input, $constraint);
        $this->inputErrorsPartNumbers->errorValidate($errors_validate);

        /* Валидация дублей */
        $count_duplicate = $this->originalRoomsRepositoryInterface
            ->numberDoubles(['original_number' => $original_number]);
        if ($count_duplicate != 0) {

            return null;
        }

        $this->originalRooms->setOriginalNumber($original_number);

        $successfully_save = $this->originalRoomsRepositoryInterface->save($this->originalRooms);

        return $successfully_save;
    }
}
