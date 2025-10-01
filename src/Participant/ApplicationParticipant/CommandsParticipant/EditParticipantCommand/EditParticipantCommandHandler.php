<?php

namespace App\Participant\ApplicationParticipant\CommandsParticipant\EditParticipantCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\Participant\ApplicationParticipant\ErrorsParticipant\InputErrorsParticipant;
use App\Participant\DomainParticipant\RepositoryInterfaceParticipant\ParticipantRepositoryInterface;
use App\Participant\ApplicationParticipant\CommandsParticipant\DTOCommands\DTOParticipantCommand\ParticipantCommand;

final class EditParticipantCommandHandler
{

    public function __construct(
        private InputErrorsParticipant $inputErrorsParticipant,
        private ParticipantRepositoryInterface $participantRepositoryInterface
    ) {}

    public function handler(ParticipantCommand $participantCommand): ?int
    {
        $edit_email = strtolower(preg_replace(
            '#\s#u',
            '',
            $participantCommand->getEmail()
        ));

        $roles = $participantCommand->getRoles();

        $isVerified = $participantCommand->isVerified();

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $input = [
            'edit_email_error' => [
                'NotBlank' => $edit_email,
                'Email' => $edit_email,
            ]
        ];

        $constraint = new Collection([
            'edit_email_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма Email не может быть пустой'
                ),
                'Email' => new Email(
                    message: 'Форма Email содержит недопустимые символы'
                )
            ])
        ]);

        $errors_validate = $validator->validate($input, $constraint);
        $this->inputErrorsParticipant->errorValidate($errors_validate);

        $id = $participantCommand->getId();
        $this->inputErrorsParticipant->emptyData($id);

        $participant = $this->participantRepositoryInterface->findParticipant($id);
        $this->inputErrorsParticipant->emptyEntity($participant);

        $this->countDuplicate($edit_email, $participant->getEmail());

        $participant->setEmail($edit_email);
        $participant->setRoles($roles);
        $participant->setVerified($isVerified);

        return $this->participantRepositoryInterface->edit($participant);
    }

    private function countDuplicate(string $edit_email, string $email): static
    {
        if ($edit_email != $email) {
            /* Валидация дублей */
            $count_duplicate = $this->participantRepositoryInterface
                ->numberDoubles(['email' => $edit_email]);
            $this->inputErrorsParticipant->errorDuplicate($count_duplicate);
        }

        return $this;
    }
}
