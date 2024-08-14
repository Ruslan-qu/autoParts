<?php

namespace App\Counterparty\ApplicationCounterparty\CommandsSaveEditDeleteCounterparty;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Counterparty\ApplicationCounterparty\CommandsSaveEditDeleteCounterparty\CreateSaveCounterpartyCommand;

final class CreateSaveCounterpartyCommandHandler
{

    public function handler(CreateSaveCounterpartyCommand $createSaveCounterpartyCommand): ?string
    {
        dd($createSaveCounterpartyCommand);
        /* Подключаем валидацию  */
        $validator = new ValidatorInterface;
        dd($validator);
        $errors_counterparty = $validator->validate($createSaveCounterpartyCommand);
        dd($errors_counterparty);
    }
}
