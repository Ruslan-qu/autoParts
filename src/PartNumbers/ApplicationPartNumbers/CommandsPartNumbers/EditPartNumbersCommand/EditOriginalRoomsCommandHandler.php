<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\EditPartNumbersCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\OriginalRoomsRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOOriginalRoomsCommand\OriginalRoomsCommand;

final class EditOriginalRoomsCommandHandler
{
    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private OriginalRoomsRepositoryInterface $originalRoomsRepositoryInterface
    ) {}

    public function handler(OriginalRoomsCommand $originalRoomsCommand): ?int
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
                    message: 'Форма Номер детали не может быть пустой'
                ),
                'Type' => new Type('string'),
                'Regex' => new Regex(
                    pattern: '/^[\da-z]*$/i',
                    message: 'Форма Номер детали содержит недопустимые символы'
                )
            ])
        ]);

        $errors_validate = $validator->validate($input, $constraint);
        $this->inputErrorsPartNumbers->errorValidate($errors_validate);

        $id = $originalRoomsCommand->getId();
        $this->inputErrorsPartNumbers->emptyData($id);

        $edit_original_number = $this->originalRoomsRepositoryInterface->findOriginalRooms($id);
        $this->inputErrorsPartNumbers->emptyEntity($edit_original_number);

        if ($original_number != $edit_original_number->getOriginalNumber()) {
            /* Валидация дублей */
            $count_duplicate = $this->originalRoomsRepositoryInterface
                ->numberDoubles(['original_number' => $original_number]);
            if ($count_duplicate != 0) {

                return null;
            }
        }

        $edit_original_number->setOriginalNumber($original_number);

        $successfully_edit = $this->originalRoomsRepositoryInterface->edit($edit_original_number);

        return $successfully_edit['edit'];
    }
}
