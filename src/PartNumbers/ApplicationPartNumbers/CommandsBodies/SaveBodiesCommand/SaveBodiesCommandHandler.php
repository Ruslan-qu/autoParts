<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsBodies\SaveBodiesCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Bodies;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\BodiesRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsBodies\DTOCommands\DTOBodiesCommand\BodiesCommand;

final class SaveBodiesCommandHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private BodiesRepositoryInterface $bodiesRepositoryInterface
    ) {}

    public function handler(BodiesCommand $bodiesCommand): ?int
    {

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $body = $bodiesCommand->getBody();

        $id_participant = $bodiesCommand->getIdParticipant();

        $input = [
            'body_error' => [
                'NotBlank' => $body,
                'Regex' => $body,
            ]
        ];

        $constraint = new Collection([
            'body_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма Кузов не может быть пустой'
                ),
                'Regex' => new Regex(
                    pattern: '/^[а-яё\s]*$/ui',
                    message: 'Форма Кузов содержит недопустимые символы'
                )
            ])
        ]);

        $errors_validate = $validator->validate($input, $constraint);
        $this->inputErrorsPartNumbers->errorValidate($errors_validate);

        /* Валидация дублей */
        $count_duplicate = $this->bodiesRepositoryInterface
            ->numberDoubles(['body' => $body]);
        $this->inputErrorsPartNumbers->errorDuplicate($count_duplicate);

        $bodies = new Bodies;
        $bodies->setBody($body);
        $bodies->setIdParticipant($id_participant);

        return $this->bodiesRepositoryInterface->save($bodies);
    }
}
