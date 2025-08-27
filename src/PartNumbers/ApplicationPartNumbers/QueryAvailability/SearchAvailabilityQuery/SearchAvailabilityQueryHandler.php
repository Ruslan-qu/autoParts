<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryAvailability\SearchAvailabilityQuery;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\AvailabilityRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryAvailability\DTOQuery\DTOAvailabilityQuery\AvailabilityQuery;


final class SearchAvailabilityQueryHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private AvailabilityRepositoryInterface $availabilityRepositoryInterface
    ) {}

    public function handler(AvailabilityQuery $availabilityQuery): ?array
    {

        $in_stock = mb_ucfirst(mb_strtolower(preg_replace(
            '#\s#',
            '',
            $availabilityQuery->getInStock()
        )));

        $id_participant = $availabilityQuery->getIdParticipant();

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $input = [
            'in_stock_error' => [
                'NotBlank' => $in_stock,
                'Regex' => $in_stock,
            ]
        ];

        $constraint = new Collection([
            'in_stock_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма Наличие не может быть пустой'
                ),
                'Regex' => new Regex(
                    pattern: '/^[а-яё]*$/ui',
                    message: 'Форма Наличие содержит недопустимые символы'
                )
            ])
        ]);

        $errors_validate = $validator->validate($input, $constraint);
        $this->inputErrorsPartNumbers->errorValidate($errors_validate);

        $find_one_by_availability['availability'] = $this->availabilityRepositoryInterface->findOneByAvailability($in_stock, $id_participant);
        $this->inputErrorsPartNumbers->issetEntity($find_one_by_availability['availability']);

        return $find_one_by_availability;
    }
}
