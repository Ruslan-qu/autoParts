<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsAxles\SaveAxlesCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Axles;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\AxlesRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsAxles\DTOCommands\DTOAxlesCommand\AxlesCommand;

final class SaveAxlesCommandHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private AxlesRepositoryInterface $axlesRepositoryInterface
    ) {}

    public function handler(AxlesCommand $axlesCommand): ?int
    {

        $axle = mb_ucfirst(mb_strtolower(preg_replace(
            '#\s#',
            '',
            $axlesCommand->getAxle()
        )));

        $id_participant = $axlesCommand->getIdParticipant();

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();
        $input = [
            'axle_error' => [
                'NotBlank' => $axle,
                'Regex' => $axle,
            ]
        ];

        $constraint = new Collection([
            'axle_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма Ось не может быть пустой'
                ),
                'Regex' => new Regex(
                    pattern: '/^[а-яё]*$/ui',
                    message: 'Форма Ось содержит недопустимые символы'
                )
            ])
        ]);

        $errors_validate = $validator->validate($input, $constraint);
        $this->inputErrorsPartNumbers->errorValidate($errors_validate);

        /* Валидация дублей */
        $count_duplicate = $this->axlesRepositoryInterface
            ->numberDoubles([
                'axle' => $axle,
                'id_participant' => $id_participant
            ]);
        $this->inputErrorsPartNumbers->errorDuplicate($count_duplicate);

        $axles = new Axles;
        $axles->setAxle($axle);
        $axles->setIdParticipant($id_participant);

        return $this->axlesRepositoryInterface->save($axles);
    }
}
