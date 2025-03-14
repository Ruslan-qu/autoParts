<?php

namespace App\Participant\ApplicationParticipant\CommandsParticipant\UserRegistrationCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\PasswordStrength;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\Participant\ApplicationParticipant\ErrorsParticipant\InputErrorsParticipant;
use App\Participant\DomainParticipant\RepositoryInterfaceParticipant\ParticipantRepositoryInterface;
use App\Participant\ApplicationParticipant\CommandsParticipant\DTOParticipantCommand\ParticipantCommand;

final class UserRegistrationCommandHandler
{

    public function __construct(
        private InputErrorsParticipant $inputErrorsParticipant,
        private ParticipantRepositoryInterface $participantRepositoryInterface,
        private Participant $participant,
        private UserPasswordHasherInterface $userPasswordHasher,
    ) {}

    public function handler(ParticipantCommand $participantCommand): int
    {

        $emailUser = $participantCommand->getEmail();

        $passwordUser = $participantCommand->getPassword();

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $input = [
            'mail_user_error' => [
                'NotBlank' => $emailUser,
                'Email' => $emailUser,
            ],
            'password_user_error' => [
                'NotBlank' => $passwordUser,
                'PasswordStrength' => $passwordUser,
            ]
        ];

        $constraint = new Collection([
            'mail_user_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма E-mail не может быть пустой'
                ),
                'Email' => new Email(
                    message: 'Форма E-mail содержит недопустимые символы'
                )
            ]),
            'password_user_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма E-mail не может быть пустой'
                ),
                'PasswordStrength' => new PasswordStrength(
                    message: 'Ваш пароль слишком легко угадать. 
                        Введите более надежный пароль.'
                )
            ])
        ]);

        $errors_validate = $validator->validate($input, $constraint);
        $this->inputErrorsParticipant->errorValidate($errors_validate);

        $this->participant->setEmail($emailUser);
        $this->participant->setRoles(['ROLE_USER']);
        $this->participant->setPassword(
            $this->userPasswordHasher->hashPassword(
                $this->participant,
                $passwordUser
            )
        );

        return $this->participantRepositoryInterface->save($this->participant);
    }
}
