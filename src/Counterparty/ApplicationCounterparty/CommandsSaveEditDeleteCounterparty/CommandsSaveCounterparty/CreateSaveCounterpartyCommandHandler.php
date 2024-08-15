<?php

namespace App\Counterparty\ApplicationCounterparty\CommandsSaveEditDeleteCounterparty\CommandsSaveCounterparty;

use Symfony\Component\Mime\Address;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\Counterparty\ApplicationCounterparty\CommandsSaveEditDeleteCounterparty\CommandsSaveCounterparty\CreateSaveCounterpartyCommand;

final class CreateSaveCounterpartyCommandHandler
{

    public function handler(CreateSaveCounterpartyCommand $createSaveCounterpartyCommand): array
    {


        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $input = [
            'name_counterparty_error' => $createSaveCounterpartyCommand->getNameCounterparty(),
            'mail_counterparty_error' => $createSaveCounterpartyCommand->getMailCounterparty(),
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
    }
}
