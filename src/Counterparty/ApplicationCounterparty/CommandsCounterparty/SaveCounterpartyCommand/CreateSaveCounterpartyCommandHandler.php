<?php

namespace App\Counterparty\ApplicationCounterparty\CommandsCounterparty\SaveCounterpartyCommand;

use Symfony\Component\Mime\Address;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;
use App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty\CounterpartyRepositoryInterface;
use App\Counterparty\ApplicationCounterparty\CommandsCounterparty\SaveCounterpartyCommand\CreateSaveCounterpartyCommand;

final class CreateSaveCounterpartyCommandHandler
{
    private $doctrine;
    private $counterparty_repository_interface;
    private $entity_counterparty;

    public function __construct(
        ManagerRegistry $doctrine,
        CounterpartyRepositoryInterface $counterparty_repository_interface,
        Counterparty $entity_counterparty
    ) {
        $this->counterparty_repository_interface = $counterparty_repository_interface;
        $this->entity_counterparty = $entity_counterparty;
        $this->doctrine = $doctrine;
    }

    public function handler(CreateSaveCounterpartyCommand $createSaveCounterpartyCommand): array
    {

        $name_counterparty = strtolower(preg_replace(
            '#\s#',
            '',
            $createSaveCounterpartyCommand->getNameCounterparty()
        ));
        $mail_counterparty = $createSaveCounterpartyCommand->getMailCounterparty();

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $input = [
            'name_counterparty_error' => $name_counterparty,
            'mail_counterparty_error' => $mail_counterparty,
        ];

        $constraint = new Collection([
            'name_counterparty_error' => new NotBlank(),
            'name_counterparty_error' => new Type('string'),
            'name_counterparty_error' => new Regex(pattern: '/^[\da-z]*$/i'),
            'mail_counterparty_error' => new NotBlank(),
            'mail_counterparty_error' => new Type('Address::class'),
            'mail_counterparty_error' => new Email(),
        ]);

        $data_errors_counterparty = $validator->validate($input, $constraint);

        if (count($data_errors_counterparty) > 0) {

            $arr_errors = [];
            foreach ($data_errors_counterparty as $key => $value_error) {

                $arr_errors[$key] = [
                    'property' => trim($value_error->getPropertyPath(), '[]'),
                    'message' => $value_error->getMessage(),
                    'value' => $value_error->getInvalidValue()
                ];
            }

            return $arr_errors;
        }
        /* Валидация дублей */
        $number_doubles = $this->counterparty_repository_interface
            ->number_doubles(['name_counterparty' => $name_counterparty, 'mail_counterparty' => $mail_counterparty]);

        if ($number_doubles == 0) {

            $this->entity_counterparty->setNameCounterparty($name_counterparty);
            $this->entity_counterparty->setMailCounterparty($mail_counterparty);

            $successfully_save = $this->counterparty_repository_interface->save($this->entity_counterparty);

            $successfully['successfully'] = $successfully_save;
            return $successfully;
        } else {
            $arr_errors['errors'] = [
                'doubles' => 'Контрагент существует'
            ];
            return $arr_errors;
        }
    }
}
