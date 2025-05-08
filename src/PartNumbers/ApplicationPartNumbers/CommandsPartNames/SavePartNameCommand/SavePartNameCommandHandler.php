<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsPartNames\SavePartNameCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartName;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\PartNameRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNames\DTOCommands\DTOPartNameCommand\PartNameCommand;

final class SavePartNameCommandHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private PartNameRepositoryInterface $partNameRepositoryInterface
    ) {}

    public function handler(PartNameCommand $partNameCommand): ?int
    {

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $part_name = $partNameCommand->getPartName();

        $id_participant = $partNameCommand->getIdParticipant();

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

        /* Валидация дублей */
        $count_duplicate = $this->partNameRepositoryInterface
            ->numberDoubles(['part_name' => $part_name]);
        $this->inputErrorsPartNumbers->errorDuplicate($count_duplicate);

        $partName = new PartName;
        $partName->setPartName($part_name);
        $partName->setIdParticipant($id_participant);

        return $this->partNameRepositoryInterface->save($partName);
    }
}
