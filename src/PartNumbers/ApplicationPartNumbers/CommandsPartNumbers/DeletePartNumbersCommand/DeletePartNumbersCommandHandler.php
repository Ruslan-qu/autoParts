<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DeletePartNumbersCommand;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\PartNumbersRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOPartNumbersObjCommand\PartNumbersObjCommand;

final class DeletePartNumbersCommandHandler
{
    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private PartNumbersRepositoryInterface $partNumbersRepositoryInterface,
    ) {}

    public function handler(PartNumbersObjCommand $partNumbersObjCommand): ?int
    {
        $partNumbers = $partNumbersObjCommand->getPartNumbers();
        $successfully_delete = $this->partNumbersRepositoryInterface->delete($partNumbers);

        return $successfully_delete['delete'];
    }
}
