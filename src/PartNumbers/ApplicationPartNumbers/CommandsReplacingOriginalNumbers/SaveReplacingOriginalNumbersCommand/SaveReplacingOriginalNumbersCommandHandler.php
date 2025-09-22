<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsReplacingOriginalNumbers\SaveReplacingOriginalNumbersCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\ReplacingOriginalNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\ReplacingOriginalNumbersRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsReplacingOriginalNumbers\DTOCommands\DTOReplacingOriginalNumbersCommand\ReplacingOriginalNumbersCommand;

final class SaveReplacingOriginalNumbersCommandHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private ReplacingOriginalNumbersRepositoryInterface $replacingOriginalNumbersRepositoryInterface
    ) {}

    public function handler(ReplacingOriginalNumbersCommand $replacingOriginalNumbersCommand): ?int
    {

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $replacing_original_number = strtolower(preg_replace(
            '#\s#u',
            '',
            $replacingOriginalNumbersCommand->getReplacingOriginalNumber()
        ));

        $id_original_number = $replacingOriginalNumbersCommand->getIdOriginalNumber();

        $id_participant = $replacingOriginalNumbersCommand->getIdParticipant();

        $input = [
            'replacing_original_number_error' => [
                'NotBlank' => $replacing_original_number,
                'Regex' => $replacing_original_number,
            ]
        ];

        $constraint = new Collection([
            'replacing_original_number_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма замена не может быть пустой'
                ),
                'Regex' => new Regex(
                    pattern: '/^[\da-z]*$/ui',
                    message: 'Форма замена содержит недопустимые символы'
                )
            ])
        ]);

        $errors_validate = $validator->validate($input, $constraint);
        $this->inputErrorsPartNumbers->errorValidate($errors_validate);

        /* Валидация дублей */
        $count_duplicate = $this->replacingOriginalNumbersRepositoryInterface
            ->numberDoubles([
                'replacing_original_number' => $replacing_original_number,
                'id_participant' => $id_participant
            ]);
        $this->inputErrorsPartNumbers->errorDuplicate($count_duplicate);

        $replacing_original_numbers = new ReplacingOriginalNumbers;
        $replacing_original_numbers->setReplacingOriginalNumber($replacing_original_number);
        $replacing_original_numbers->setIdOriginalNumber($id_original_number);
        $replacing_original_numbers->setIdParticipant($id_participant);

        $id = $this->replacingOriginalNumbersRepositoryInterface->save($replacing_original_numbers);

        return $id;
    }
}
