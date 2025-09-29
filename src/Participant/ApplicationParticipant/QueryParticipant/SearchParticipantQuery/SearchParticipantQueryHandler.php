<?php

namespace App\Participant\ApplicationParticipant\QueryParticipant\SearchParticipantQuery;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\Participant\ApplicationParticipant\ErrorsParticipant\InputErrorsParticipant;
use App\Participant\DomainParticipant\RepositoryInterfaceParticipant\ParticipantRepositoryInterface;
use App\Participant\ApplicationParticipant\QueryParticipant\DTOQuery\DTOParticipantQuery\ParticipantQuery;

final class SearchParticipantQueryHandler
{

    public function __construct(
        private InputErrorsParticipant $inputErrorsParticipant,
        private ParticipantRepositoryInterface $participantRepositoryInterface,
        private Security $security
    ) {}

    public function handler(ParticipantQuery $participantQuery): ?Participant
    {

        $email = strtolower(preg_replace(
            '#\s#u',
            '',
            $participantQuery->getEmail()
        ));

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $input = [
            'email_error' => [
                'NotBlank' => $email,
                'Email' => $email,
            ]
        ];

        $constraint = new Collection([
            'email_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма Поставщик не может быть пустой'
                ),
                'Email' => new Email(
                    message: 'Форма E-mail содержит недопустимые символы'
                )
            ])
        ]);

        $errors_validate = $validator->validate($input, $constraint);
        $this->inputErrorsParticipant->errorValidate($errors_validate);

        $arr_еntity = $this->participantRepositoryInterface->findOneByParticipant($email);
        $this->inputErrorsParticipant->emptyEntity($arr_еntity)->roleVerification($arr_еntity, $this->security);

        return $arr_еntity;
    }
}
