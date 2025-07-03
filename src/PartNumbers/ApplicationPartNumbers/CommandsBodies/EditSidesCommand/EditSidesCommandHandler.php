<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsSides\EditSidesCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\SidesRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsSides\DTOCommands\DTOSidesCommand\SidesCommand;

final class EditSidesCommandHandler
{
    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private SidesRepositoryInterface $sidesRepositoryInterface
    ) {}

    public function handler(SidesCommand $sidesCommand): ?int
    {
        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $edit_side = $sidesCommand->getSide();

        $input = [
            'side_error' => [
                'NotBlank' => $edit_side,
                'Regex' => $edit_side,
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


        $id = $sidesCommand->getId();
        $this->inputErrorsPartNumbers->emptyData($id);

        $sides = $this->sidesRepositoryInterface->findSides($id);
        $this->inputErrorsPartNumbers->emptyEntity($sides);

        $this->countDuplicate($edit_side, $sides->getSide());

        $sides->setSide($edit_side);

        $successfully_edit = $this->sidesRepositoryInterface->edit($sides);

        $id = $successfully_edit['edit'];

        return $id;
    }

    private function countDuplicate(string $edit_side, string $side): static
    {
        if ($edit_side != $side) {
            /* Валидация дублей */
            $count_duplicate = $this->sidesRepositoryInterface
                ->numberDoubles(['side' => $edit_side]);
            $this->inputErrorsPartNumbers->errorDuplicate($count_duplicate);
        }

        return $this;
    }
}
