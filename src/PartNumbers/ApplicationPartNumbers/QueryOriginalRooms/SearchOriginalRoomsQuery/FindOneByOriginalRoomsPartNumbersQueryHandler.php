<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryOriginalRooms\SearchOriginalRoomsQuery;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Collection;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\OriginalRooms;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\OriginalRoomsRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryOriginalRooms\DTOQuery\DTOOriginalRoomsQuery\OriginalRoomsQuery;

final class FindOneByOriginalRoomsPartNumbersQueryHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private OriginalRoomsRepositoryInterface $originalRoomsRepositoryInterface
    ) {}

    public function handler(OriginalRoomsQuery $originalRoomsQuery): ?OriginalRooms
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
                'Regex' => $original_number,
            ]
        ];

        $constraint = new Collection([
            'original_number_error' => new Collection([
                'Regex' => new Regex(
                    pattern: '/^[\da-z]*$/ui',
                    message: 'Форма оригинальный номер содержит недопустимые символы'
                )
            ])
        ]);

        $errors_validate = $validator->validate($input, $constraint);
        $this->inputErrorsPartNumbers->errorValidate($errors_validate);

        $find_one_by_original_rooms =
            $this->originalRoomsRepositoryInterface->findOneByOriginalRooms($original_number, $id_participant);
        if ($find_one_by_original_rooms === null) {
            $this->inputErrorsPartNumbers->issetOriginalRooms($find_one_by_original_rooms);
        }
        return $find_one_by_original_rooms;
    }
}
