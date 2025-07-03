<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsSides\SaveSidesCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Sides;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\SidesRepositoryInterface;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\BodiesRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsSides\DTOCommands\DTOSidesCommand\SidesCommand;

final class SaveBodiesCommandHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private BodiesRepositoryInterface $bodiesRepositoryInterface
    ) {}

    public function handler(BodiesCommand $sidesCommand): ?int
    {

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $side = $sidesCommand->getSide();

        $id_participant = $sidesCommand->getIdParticipant();

        $input = [
            'side_error' => [
                'NotBlank' => $side,
                'Regex' => $side,
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

        /* Валидация дублей */
        $count_duplicate = $this->sidesRepositoryInterface
            ->numberDoubles(['side' => $side]);
        $this->inputErrorsPartNumbers->errorDuplicate($count_duplicate);

        $sides = new Sides;
        $sides->setSide($side);
        $sides->setIdParticipant($id_participant);

        return $this->sidesRepositoryInterface->save($sides);
    }
}
