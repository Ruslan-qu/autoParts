<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsReplacingOriginalNumbers\EditReplacingOriginalNumbersCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\ReplacingOriginalNumbersRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsReplacingOriginalNumbers\DTOCommands\DTOReplacingOriginalNumbersCommand\ReplacingOriginalNumbersCommand;

final class EditReplacingOriginalNumbersCommandHandler
{
    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private ReplacingOriginalNumbersRepositoryInterface $replacingOriginalNumbersRepositoryInterface
    ) {}

    public function handler(ReplacingOriginalNumbersCommand $replacingOriginalNumbersCommand): ?int
    {
        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $edit_replacing_original_number = strtolower(preg_replace(
            '#\s#u',
            '',
            $replacingOriginalNumbersCommand->getReplacingOriginalNumber()
        ));

        $input = [
            'edit_replacing_original_number_error' => [
                'NotBlank' => $edit_replacing_original_number,
                'Regex' => $edit_replacing_original_number,
            ]
        ];

        $constraint = new Collection([
            'edit_replacing_original_number_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма Замена не может быть пустой'
                ),
                'Regex' => new Regex(
                    pattern: '/^[\da-z]*$/ui',
                    message: 'Форма Замена содержит недопустимые символы'
                )
            ])
        ]);

        $id_original_number = $replacingOriginalNumbersCommand->getIdOriginalNumber();

        $errors_validate = $validator->validate($input, $constraint);
        $this->inputErrorsPartNumbers->errorValidate($errors_validate);

        $id = $replacingOriginalNumbersCommand->getId();
        $this->inputErrorsPartNumbers->emptyData($id);

        $replacing_original_numbers = $this->replacingOriginalNumbersRepositoryInterface->findReplacingOriginalNumbers($id);
        $this->inputErrorsPartNumbers->emptyEntity($replacing_original_numbers);

        $this->countDuplicate($edit_replacing_original_number, $replacing_original_numbers->getReplacingOriginalNumber());

        $replacing_original_numbers->setReplacingOriginalNumber($edit_replacing_original_number);
        $replacing_original_numbers->setIdOriginalNumber($id_original_number);

        $id = $this->replacingOriginalNumbersRepositoryInterface->edit($replacing_original_numbers);

        return $id;
    }

    private function countDuplicate(string $edit_replacing_original_number, string $replacing_original_number): static
    {
        if ($edit_replacing_original_number != $replacing_original_number) {
            /* Валидация дублей */
            $count_duplicate = $this->replacingOriginalNumbersRepositoryInterface
                ->numberDoubles(['replacing_original_number' => $edit_replacing_original_number]);
            $this->inputErrorsPartNumbers->errorDuplicate($count_duplicate);
        }

        return $this;
    }
}
