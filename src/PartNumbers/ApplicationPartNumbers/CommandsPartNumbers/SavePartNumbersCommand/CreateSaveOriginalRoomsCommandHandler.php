<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\SavePartNumbersCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\OriginalRooms;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\OriginalRoomsRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOOriginalRoomsCommand\CreateOriginalRoomsCommand;

final class CreateSaveOriginalRoomsCommandHandler
{
    private $original_rooms_repository_interface;
    private $original_rooms;

    public function __construct(
        OriginalRoomsRepositoryInterface $originalRoomsRepositoryInterface,
        OriginalRooms $originalRooms
    ) {
        $this->original_rooms_repository_interface = $originalRoomsRepositoryInterface;
        $this->original_rooms = $originalRooms;
    }

    public function handler(CreateOriginalRoomsCommand $createOriginalRoomsCommand): array
    {

        $original_number = strtolower(preg_replace(
            '#\s#',
            '',
            $createOriginalRoomsCommand->getOriginalNumber()
        ));

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $input = [
            'original_number_error' => [
                'Type' => $original_number,
                'Regex' => $original_number,
            ]
        ];

        $constraint = new Collection([
            'original_number_error' => new Collection([
                'Type' => new Type('string'),
                'Regex' => new Regex(
                    pattern: '/^[\da-z]*$/i',
                    message: 'Форма Оригинальный номер содержит недопустимые символы'
                )
            ])
        ]);

        $data_errors_original_rooms = [];
        foreach ($validator->validate($input, $constraint) as $key => $value_error) {

            $data_errors_original_rooms[$key] = [
                $value_error->getPropertyPath() => $value_error->getMessage()
            ];
        }

        if (!empty($data_errors_original_rooms)) {

            $json_arr_data_errors = json_encode($data_errors_original_rooms, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }
        /* Валидация дублей */
        $number_doubles = $this->original_rooms_repository_interface
            ->numberDoubles(['original_number' => $original_number]);

        if ($number_doubles != 0) {

            $arr_errors_number_doubles['errors'] = [
                'doubles' => 'Оригинальный номер существует'
            ];

            return $arr_errors_number_doubles;
        }

        $this->original_rooms->setOriginalNumber($original_number);


        $successfully_save = $this->original_rooms_repository_interface->save($this->original_rooms);

        $successfully['successfully'] = $successfully_save;
        return $successfully;
    }
}
