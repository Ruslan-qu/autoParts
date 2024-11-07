<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\SavePartNumbersCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\OriginalRooms;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\OriginalRoomsRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOOriginalRoomsCommand\OriginalRoomsCommand;

final class SaveOriginalRoomsCommandHandler
{

    public function __construct(
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
        $number_doubles = $this->originalRoomsRepositoryInterface
            ->numberDoubles(['original_number' => $original_number]);

        if ($number_doubles != 0) {

            return null;
        }

        $this->originalRooms->setOriginalNumber($original_number);


        $successfully_save = $this->originalRoomsRepositoryInterface->save($this->originalRooms);

        return $successfully_save;
    }
}
