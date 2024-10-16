<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\EditPartNumbersCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\PartNumbers\InfrastructurePartNumbers\RepositoryPartNumbers\OriginalRoomsRepository;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\OriginalRoomsRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOOriginalRoomsCommand\CreateOriginalRoomsCommand;

final class EditOriginalRoomsCommandHandler
{
    public function __construct(
        private OriginalRoomsRepositoryInterface $originalRoomsRepositoryInterface
    ) {}

    public function handler(CreateOriginalRoomsCommand $createOriginalRoomsCommand): ?array
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

            $arr_data_errors = ['Error' => 'Иди некорректное'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        $edit_original_number = $this->originalRoomsRepositoryInterface->findOriginalRooms($id);

        if (empty($edit_original_number)) {

            $arr_data_errors = ['Error' => 'Иди некорректное'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        if ($original_number != $edit_original_number->getOriginalNumber()) {
            /* Валидация дублей */
            $number_doubles = $this->originalRoomsRepositoryInterface
                ->numberDoubles(['original_number' => $original_number]);

            if ($number_doubles != 0) {

                return null;
            }
        }


        $edit_original_number->setOriginalNumber($original_number);


        $successfully_edit = $this->originalRoomsRepositoryInterface->edit($edit_original_number);

        $successfully['successfully'] = $successfully_edit;

        return $successfully;
    }
}
