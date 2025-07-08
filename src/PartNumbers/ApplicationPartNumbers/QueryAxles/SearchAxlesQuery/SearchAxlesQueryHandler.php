<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryAxles\SearchAxlesQuery;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\ApplicationPartNumbers\QueryAxles\DTOQuery\DTOAxlesQuery\AxlesQuery;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\AxlesRepositoryInterface;


final class SearchAxlesQueryHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private AxlesRepositoryInterface $axlesRepositoryInterface
    ) {}

    public function handler(AxlesQuery $axlesQuery): ?array
    {

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $axle = $axlesQuery->getAxle();
        $id_participant = $axlesQuery->getIdParticipant();

        $input = [
            'axle_error' => [
                'NotBlank' => $axle,
                'Regex' => $axle,
            ]
        ];

        $constraint = new Collection([
            'axle_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма Ось не может быть пустой'
                ),
                'Regex' => new Regex(
                    pattern: '/^[а-яё\s]*$/ui',
                    message: 'Форма Ось содержит недопустимые символы'
                )
            ])
        ]);

        $errors_validate = $validator->validate($input, $constraint);
        $this->inputErrorsPartNumbers->errorValidate($errors_validate);

        $find_one_by_axles['axles'] = $this->axlesRepositoryInterface->findOneByAxles($axle, $id_participant);
        $this->inputErrorsPartNumbers->issetEntity($find_one_by_axles['axles']);

        return $find_one_by_axles;
    }
}
