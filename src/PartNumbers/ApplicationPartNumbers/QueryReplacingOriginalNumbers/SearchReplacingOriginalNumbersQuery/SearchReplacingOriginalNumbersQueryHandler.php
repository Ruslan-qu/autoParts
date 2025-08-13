<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryReplacingOriginalNumbers\SearchReplacingOriginalNumbersQuery;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\ReplacingOriginalNumbersRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryReplacingOriginalNumbers\DTOQuery\DTOReplacingOriginalNumbersQuery\ReplacingOriginalNumbersQuery;


final class SearchReplacingOriginalNumbersQueryHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private ReplacingOriginalNumbersRepositoryInterface $replacingOriginalNumbersRepositoryInterface
    ) {}

    public function handler(ReplacingOriginalNumbersQuery $replacingOriginalNumbersQuery): ?array
    {

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $replacing_original_number = strtolower(preg_replace(
            '#\s#u',
            '',
            $replacingOriginalNumbersQuery->getReplacingOriginalNumber()
        ));

        $id_participant = $replacingOriginalNumbersQuery->getIdParticipant();

        $input = [
            'replacing_original_number_error' => [
                'NotBlank' => $replacing_original_number,
                'Regex' => $replacing_original_number,
            ]
        ];

        $constraint = new Collection([
            'replacing_original_number_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма Замена не может быть пустой'
                ),
                'Regex' => new Regex(
                    pattern: '/^[\da-z]*$/ui',
                    message: 'Форма Замена содержит недопустимые символы'
                )
            ])
        ]);

        $errors_validate = $validator->validate($input, $constraint);
        $this->inputErrorsPartNumbers->errorValidate($errors_validate);

        $find_one_by_replacing_original_number['replacingOriginalNumbers'] =
            $this->replacingOriginalNumbersRepositoryInterface->findOneByReplacingOriginalNumbers(
                $replacing_original_number,
                $id_participant
            );
        $this->inputErrorsPartNumbers->issetEntity($find_one_by_replacing_original_number['replacingOriginalNumbers']);

        return $find_one_by_replacing_original_number;
    }
}
