<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsAxles\DeleteAxlesCommand;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\AxlesRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsAxles\DTOCommands\DTOAxlesObjCommand\AxlesObjCommand;

final class DeleteAxlesCommandHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private AxlesRepositoryInterface $axlesRepositoryInterface,
    ) {}

    public function handler(AxlesObjCommand $axlesObjCommand): ?int
    {
        $axles = $axlesObjCommand->getAxles();
        $successfully_delete = $this->axlesRepositoryInterface->delete($axles);

        return $successfully_delete['delete'];
    }
}
