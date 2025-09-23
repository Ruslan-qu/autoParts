<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsAxles\EditAxlesCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\AxlesRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsAxles\DTOCommands\DTOAxlesCommand\AxlesCommand;

final class EditAxlesCommandHandler
{
    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private AxlesRepositoryInterface $axlesRepositoryInterface
    ) {}

    public function handler(AxlesCommand $axlesCommand): ?int
    {

        $edit_axle = mb_ucfirst(mb_strtolower(preg_replace(
            '#\s#',
            '',
            $axlesCommand->getAxle()
        )));

        $participant = $axlesCommand->getIdParticipant();

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();
        $input = [
            'axle_error' => [
                'NotBlank' => $edit_axle,
                'Regex' => $edit_axle,
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


        $id = $axlesCommand->getId();
        $this->inputErrorsPartNumbers->emptyData($id);

        $axles = $this->axlesRepositoryInterface->findAxles($id);
        $this->inputErrorsPartNumbers->emptyEntity($axles);

        $this->countDuplicate($edit_axle, $axles->getAxle(), $participant);

        $axles->setAxle($edit_axle);

        $successfully_edit = $this->axlesRepositoryInterface->edit($axles);

        $id = $successfully_edit['edit'];

        return $id;
    }

    private function countDuplicate(string $edit_axle, string $axle, Participant $participant): static
    {
        if ($edit_axle != $axle) {
            /* Валидация дублей */
            $count_duplicate = $this->axlesRepositoryInterface
                ->numberDoubles([
                    'axle' => $edit_axle,
                    'id_participant' => $participant
                ]);
            $this->inputErrorsPartNumbers->errorDuplicate($count_duplicate);
        }

        return $this;
    }
}
