<?php

namespace App\Participant\ApplicationParticipant\CommandsParticipant\UserRegistrationCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\Participant\ApplicationParticipant\ErrorsParticipant\InputErrorsParticipant;
use App\Counterparty\ApplicationCounterparty\CommandsCounterparty\DTOCommands\CounterpartyCommand;
use App\Participant\DomainParticipant\RepositoryInterfaceParticipant\ParticipantRepositoryInterface;
use App\Participant\ApplicationParticipant\CommandsParticipant\DTOParticipantCommand\ParticipantCommand;

final class UserRegistrationCommandHandler
{

    public function __construct(
        private InputErrorsParticipant $inputErrorsParticipant,
        private ParticipantRepositoryInterface $participantRepositoryInterface,
        private Participant $participant
    ) {}

    public function handler(ParticipantCommand $participantCommand): int
    {

        $emailUser = $participantCommand->getEmail();

        $passwordUser = $participantCommand->getPassword();

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $input = [
            'name_counterparty_error' => [
                'NotBlank' => $emailUser,
                'Email' => $emailUser,
            ],
            'mail_counterparty_error' => [
                'NotBlank' => $passwordUser,
                'Email' => $passwordUser,
            ]
        ];

        $constraint = new Collection([
            'name_counterparty_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма Поставщик не может быть пустой'
                ),
                'Regex' => new Regex(
                    pattern: '/^[\da-z]*$/i',
                    message: 'Форма Поставщик содержит недопустимые символы'
                )
            ]),
            'mail_counterparty_error' => new Collection([
                'Email' => new Email(
                    message: 'Форма E-mail содержит недопустимые символы'
                )
            ])
        ]);

        $errors_validate = $validator->validate($input, $constraint);
        $this->inputErrorsParticipant->errorValidate($errors_validate);

        /* Валидация дублей */
        $count_duplicate = $this->participantRepositoryInterface
            ->numberDoubles(['name_counterparty' => $name_counterparty]);
        $this->inputErrorsParticipant->errorDuplicate($count_duplicate);

        $this->counterparty->setNameCounterparty($name_counterparty);
        $this->counterparty->setMailCounterparty($mail_counterparty);
        $this->counterparty->setManagerPhone($manager_phone);
        $this->counterparty->setDeliveryPhone($delivery_phone);

        $successfully_save = $this->participantRepositoryInterface->save($this->counterparty);

        $id = $successfully_save['save'];
        return $id;
    }
}
