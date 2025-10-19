<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryPartNames\SearchPartNameQuery;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\PartNameRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNames\DTOQuery\DTOPartNameQuery\PartNameQuery;



final class SearchPartNameQueryHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private PartNameRepositoryInterface $partNameRepositoryInterface
    ) {}

    public function handler(PartNameQuery $partNameQuery): ?array
    {

        $part_name = mb_ucfirst(mb_strtolower(
            $partNameQuery->getPartName()
        ));

        $id_participant = $partNameQuery->getIdParticipant();

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();
        $input = [
            'part_name_error' => [
                'NotBlank' => $part_name,
                'Regex' => $part_name,
            ]
        ];

        $constraint = new Collection([
            'part_name_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма Название детали не может быть пустой'
                ),
                'Regex' => new Regex(
                    pattern: '/^[а-яё\s]*$/ui',
                    message: 'Форма Название детали содержит недопустимые символы'
                )
            ])
        ]);

        $errors_validate = $validator->validate($input, $constraint);
        $this->inputErrorsPartNumbers->errorValidate($errors_validate);

        $find_one_by_part_name['part_name'] = $this->partNameRepositoryInterface->findOneByPartName($part_name, $id_participant);
        $this->inputErrorsPartNumbers->issetEntity($find_one_by_part_name['part_name']);

        return $find_one_by_part_name;
    }
}
