<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsReplacingOriginalNumbers\DeleteReplacingOriginalNumbersCommand;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\ReplacingOriginalNumbersRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsReplacingOriginalNumbers\DTOCommands\DTOReplacingOriginalNumbersObjCommand\ReplacingOriginalNumbersObjCommand;

final class DeleteReplacingOriginalNumbersCommandHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private ReplacingOriginalNumbersRepositoryInterface $replacingOriginalNumbersRepositoryInterface,
    ) {}

    public function handler(ReplacingOriginalNumbersObjCommand $replacingOriginalNumbersObjCommand): ?int
    {
        $replacing_original_numbers = $replacingOriginalNumbersObjCommand->getReplacingOriginalNumbers();
        $successfully_delete = $this->replacingOriginalNumbersRepositoryInterface->delete($replacing_original_numbers);

        return $successfully_delete['delete'];
    }
}
