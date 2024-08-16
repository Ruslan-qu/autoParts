<?php

namespace App\Counterparty\ApplicationCounterparty\CommandsSaveEditDeleteCounterparty\CommandsSaveCounterparty;

use Symfony\Component\Mime\Address;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\Counterparty\InfrastructureCounterparty\RepositoryCounterparty\CounterpartyRepository;
use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;
use App\Counterparty\DomainCounterparty\RepositoryInterfaceCounterparty\CounterpartyRepositoryInterface;
use App\Counterparty\ApplicationCounterparty\CommandsSaveEditDeleteCounterparty\CommandsSaveCounterparty\CreateSaveCounterpartyCommand;

final class CreateSaveCounterpartyCommandHandler
{

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
            // dd($arr_errors);
            return $arr_errors;
        }

        $counterpartyr_repository_interface = new CounterpartyRepository();
        $number_doubles = $counterpartyr_repository_interface
            ->number_doubles(['name_counterparty' => $name_counterparty, 'mail_counterparty' => $mail_counterparty]);
        dd($number_doubles);
        /* Валидация дублей */
        if ($сount_counterparty == 0) {

            if ($сount_mail_counterparty == 0) {
                //dd($сount_mail_counterparty);
                $entity_counterparty->setCounterparty($counterparty_strtolower_preg_replace);

                $entity_counterparty->setMailCounterparty($mail_counterparty_strtolower_preg_replace);


                $em = $doctrine->getManager();
                $em->persist($entity_counterparty);
                $em->flush();
            } else {

                $this->addFlash('children[mail_counterparty].data_sales', 'Такой email существует');
                $this->addFlash('mail_counterparty_sales', $mail_counterparty_strtolower_preg_replace);
            }
        } else {
            //dd(1);
            $this->addFlash('children[counterparty].data_sales', 'Такой поставщик существует');
            $this->addFlash('counterparty_sales', $counterparty_strtolower_preg_replace);
        }
    }
}
