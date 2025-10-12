<?php

namespace App\Participant\ApplicationParticipant\CommandsParticipant\UserRegistrationCommand;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Mailer\Transport\SendmailTransport;
use Symfony\Component\Validator\Constraints\Email as VEmail;
use Symfony\Component\Validator\Constraints\PasswordStrength;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\Participant\ApplicationParticipant\ErrorsParticipant\InputErrorsParticipant;
use App\Participant\DomainParticipant\RepositoryInterfaceParticipant\ParticipantRepositoryInterface;
use App\Participant\ApplicationParticipant\CommandsParticipant\DTOCommands\DTOParticipantRegistrationCommand\ParticipantRegistrationCommand;

final class UserRegistrationCommandHandler
{

    public function __construct(
        private InputErrorsParticipant $inputErrorsParticipant,
        private ParticipantRepositoryInterface $participantRepositoryInterface,
        private Participant $participant,
        private UserPasswordHasherInterface $userPasswordHasher,
        private MailerInterface $mailer
    ) {}

    public function handler(ParticipantRegistrationCommand $participantRegistrationCommand): int
    {

        $emailUser = strtolower(preg_replace(
            '#\s#u',
            '',
            $participantRegistrationCommand->getEmail()
        ));

        $passwordUser = $participantRegistrationCommand->getPassword();
        /*$imap = imap_open(
            '{imap.mail.ru:993/imap/ssl/novalidate-cert}INBOX',
            'imap_test_test_test@mail.ru',
            'jVRBymTQUhzvExwcka67'
        );*/

        try {
            //$transport = new SendmailTransport('/usr/sbin/sendmail -t');
            // $mailer = new Mailer($transport);
            $email = (new Email())
                ->from('imap_test_test_test@mail.ru')
                ->to('ququeptee@mail.ru')
                //->addTo('imap_test_test_test@mail.ru')
                ->subject('Вы зарегистрированы на сайте')
                ->text('Вы зарегистрировались на тестовым сайте "учет автодеталей и не только" 
            посмотрите если понравится, пишите отправлю сылку на github, 
            Ваш Емайл : ' . $emailUser . ' Ваш пароль : ' . $passwordUser);

            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            dd($e);
        }
        dd($email);
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
                'Email' => new VEmail(
                    message: 'Форма E-mail содержит недопустимые символы'
                )
            ]),
            'password_user_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма Password не может быть пустой'
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
        $id = $this->participantRepositoryInterface->save($this->participant);



        return $id;
    }
}
