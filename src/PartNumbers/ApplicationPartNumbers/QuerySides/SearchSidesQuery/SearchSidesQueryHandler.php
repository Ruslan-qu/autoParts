<?php

namespace App\PartNumbers\ApplicationPartNumbers\QuerySides\SearchSidesQuery;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\ApplicationPartNumbers\QuerySides\DTOQuery\DTOSidesQuery\SidesQuery;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\SidesRepositoryInterface;


final class SearchSidesQueryHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private SidesRepositoryInterface $sidesRepositoryInterface
    ) {}

    public function handler(SidesQuery $sidesQuery): ?array
    {

        $side = mb_ucfirst(mb_strtolower(
            $sidesQuery->getSide()
        ));

        $id_participant = $sidesQuery->getIdParticipant();

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();
        $input = [
            'side_error' => [
                'NotBlank' => $side,
                'Regex' => $side,
            ]
        ];

        $constraint = new Collection([
            'side_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма Сторона не может быть пустой'
                ),
                'Regex' => new Regex(
                    pattern: '/^[а-яё\s]*$/ui',
                    message: 'Форма Сторона содержит недопустимые символы'
                )
            ])
        ]);

        $errors_validate = $validator->validate($input, $constraint);
        $this->inputErrorsPartNumbers->errorValidate($errors_validate);

        $find_one_by_sides['sides'] = $this->sidesRepositoryInterface->findOneBySides($side, $id_participant);
        $this->inputErrorsPartNumbers->issetEntity($find_one_by_sides['sides']);

        return $find_one_by_sides;
    }
}
