<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryOriginalRooms\SearchOriginalRoomsQuery;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\OriginalRoomsRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryOriginalRooms\DTOQuery\DTOOriginalRoomsQuery\OriginalRoomsQuery;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\ReplacingOriginalNumbersRepositoryInterface;

final class SearchOriginalRoomsQueryHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private OriginalRoomsRepositoryInterface $originalRoomsRepositoryInterface,
        private ReplacingOriginalNumbersRepositoryInterface $replacingOriginalNumbersRepositoryInterface
    ) {}

    public function handler(OriginalRoomsQuery $originalRoomsQuery): ?array
    {

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $original_number = strtolower(preg_replace(
            '#\s#u',
            '',
            $originalRoomsQuery->getOriginalNumber()
        ));

        $id_participant = $originalRoomsQuery->getIdParticipant();

        $input = [
            'original_number_error' => [
                'NotBlank' => $original_number,
                'Regex' => $original_number,
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
            ])
        ]);

        $errors_validate = $validator->validate($input, $constraint);
        $this->inputErrorsPartNumbers->errorValidate($errors_validate);

        $find_one_by_original_rooms =
            $this->replacingOriginalNumbersRepositoryInterface->findOneByOriginalNumbers($original_number, $id_participant);

        if (empty($find_one_by_original_rooms)) {
            $find_one_by_original_rooms['originalRooms'] =
                $this->originalRoomsRepositoryInterface->findOneByOriginalRooms($original_number, $id_participant);
            $this->inputErrorsPartNumbers->issetEntity($find_one_by_original_rooms['originalRooms']);
        }

        return $find_one_by_original_rooms;
    }
}
