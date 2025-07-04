<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryBodies\SearchBodiesQuery;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\ApplicationPartNumbers\QueryBodies\DTOQuery\DTOBodiesQuery\BodiesQuery;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\BodiesRepositoryInterface;


final class SearchBodiesQueryHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private BodiesRepositoryInterface $bodiesRepositoryInterface
    ) {}

    public function handler(BodiesQuery $bodiesQuery): ?array
    {

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $body = $bodiesQuery->getBody();
        $id_participant = $bodiesQuery->getIdParticipant();

        $input = [
            'side_error' => [
                'NotBlank' => $body,
                'Regex' => $body,
            ]
        ];

        $constraint = new Collection([
            'side_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма Кузов не может быть пустой'
                ),
                'Regex' => new Regex(
                    pattern: '/^[а-яё\s]*$/ui',
                    message: 'Форма Кузов содержит недопустимые символы'
                )
            ])
        ]);

        $errors_validate = $validator->validate($input, $constraint);
        $this->inputErrorsPartNumbers->errorValidate($errors_validate);

        $find_one_by_bodies['bodies'] = $this->bodiesRepositoryInterface->findOneByBodies($body, $id_participant);
        $this->inputErrorsPartNumbers->issetEntity($find_one_by_bodies);

        return $find_one_by_bodies;
    }
}
