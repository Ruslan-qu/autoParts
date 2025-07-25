<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsAvailability\SaveAvailabilityCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Availability;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\AvailabilityRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsAvailability\DTOCommands\DTOAvailabilityCommand\AvailabilityCommand;

final class SaveAvailabilityCommandHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private AvailabilityRepositoryInterface $availabilityRepositoryInterface
    ) {}

    public function handler(AvailabilityCommand $availabilityCommand): ?int
    {

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $in_stock = $availabilityCommand->getInStock();

        $id_participant = $availabilityCommand->getIdParticipant();

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
                    pattern: '/^[а-яё\s]*$/ui',
                    message: 'Форма Наличие содержит недопустимые символы'
                )
            ])
        ]);

        $errors_validate = $validator->validate($input, $constraint);
        $this->inputErrorsPartNumbers->errorValidate($errors_validate);

        /* Валидация дублей */
        $count_duplicate = $this->availabilityRepositoryInterface
            ->numberDoubles(['in_stock' => $in_stock]);
        $this->inputErrorsPartNumbers->errorDuplicate($count_duplicate);

        $availability = new Availability;
        $availability->setInStock($in_stock);
        $availability->setIdParticipant($id_participant);

        return $this->availabilityRepositoryInterface->save($availability);
    }
}
