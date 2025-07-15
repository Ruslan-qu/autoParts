<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsAvailability\EditAvailabilityCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\AvailabilityRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsAvailability\DTOCommands\DTOAvailabilityCommand\AvailabilityCommand;

final class EditAvailabilityCommandHandler
{
    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private AvailabilityRepositoryInterface $availabilityRepositoryInterface
    ) {}

    public function handler(AvailabilityCommand $availabilityCommand): ?int
    {
        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $edit_in_stock = $availabilityCommand->getInStock();

        $input = [
            'in_stock_error' => [
                'NotBlank' => $edit_in_stock,
                'Regex' => $edit_in_stock,
            ]
        ];

        $constraint = new Collection([
            'in_stock_error' => new Collection([
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


        $id = $availabilityCommand->getId();
        $this->inputErrorsPartNumbers->emptyData($id);

        $availability = $this->availabilityRepositoryInterface->findAvailability($id);
        $this->inputErrorsPartNumbers->emptyEntity($availability);

        $this->countDuplicate($edit_in_stock, $availability->getInStock());

        $availability->setInStock($edit_in_stock);

        $successfully_edit = $this->availabilityRepositoryInterface->edit($availability);

        $id = $successfully_edit['edit'];

        return $id;
    }

    private function countDuplicate(string $edit_in_stock, string $in_stock): static
    {
        if ($edit_in_stock != $in_stock) {
            /* Валидация дублей */
            $count_duplicate = $this->availabilityRepositoryInterface
                ->numberDoubles(['in_stock' => $edit_in_stock]);
            $this->inputErrorsPartNumbers->errorDuplicate($count_duplicate);
        }

        return $this;
    }
}
