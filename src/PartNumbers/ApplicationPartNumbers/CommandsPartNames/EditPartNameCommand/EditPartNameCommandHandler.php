<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsPartNames\EditPartNameCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\PartNameRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNames\DTOCommands\DTOPartNameCommand\PartNameCommand;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOPartNumbersCommand\PartNumbersCommand;

final class EditPartNameCommandHandler
{
    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private PartNameRepositoryInterface $partNameRepositoryInterface
    ) {}

    public function handler(PartNameCommand $partNameCommand): ?int
    {
        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $edit_part_name = $partNameCommand->getPartName();

        $input = [
            'part_name_error' => [
                'NotBlank' => $edit_part_name,
                'Regex' => $edit_part_name,
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


        $id = $partNameCommand->getId();
        $this->inputErrorsPartNumbers->emptyData($id);

        $part_name = $this->partNameRepositoryInterface->findPartName($id);
        $this->inputErrorsPartNumbers->emptyEntity($part_name);

        $this->countDuplicate($edit_part_name, $part_name->getPartName());

        $part_name->setPartName($edit_part_name);


        $successfully_edit = $this->partNameRepositoryInterface->edit($part_name);

        $id = $successfully_edit['edit'];

        return $id;
    }

    private function countDuplicate(string $edit_part_name, string $part_name): static
    {
        if ($edit_part_name != $part_name) {
            /* Валидация дублей */
            $count_duplicate = $this->partNameRepositoryInterface
                ->numberDoubles(['part_name' => $edit_part_name]);
            $this->inputErrorsPartNumbers->errorDuplicate($count_duplicate);
        }

        return $this;
    }
}
