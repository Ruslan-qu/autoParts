<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\EditPartNumbersCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\PartNumbers\InfrastructurePartNumbers\RepositoryPartNumbers\OriginalRoomsRepository;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOOriginalRoomsCommand\CreateOriginalRoomsCommand;

final class CreateEditOriginalRoomsCommandHandler
{
    private $original_rooms_repository;

    public function __construct(
        OriginalRoomsRepository $originalRoomsRepository
    ) {
        $this->original_rooms_repository = $originalRoomsRepository;
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

        $data_errors_original_number = [];
        foreach ($validator->validate($input, $constraint) as $key => $value_error) {

            $data_errors_original_number[$key] = [
                $value_error->getPropertyPath() => $value_error->getMessage()
            ];
        }

        if (!empty($data_errors_original_number)) {

            $json_arr_data_errors = json_encode($data_errors_original_number, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        $id = $createOriginalRoomsCommand->getId();

        if (empty($id)) {

            return null;
        }

        $edit_original_number = $this->original_rooms_repository->findOriginalRooms($id);

        if (empty($edit_original_number)) {

            return null;
        }

        $edit_original_number->setOriginalNumber($original_number);


        $successfully_edit = $this->original_rooms_repository->edit($edit_original_number);

        $successfully['successfully'] = $successfully_edit;

        return $successfully;
    }
}
