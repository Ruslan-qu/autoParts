<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsSides\DeleteSidesCommand;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\SidesRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsSides\DTOCommands\DTOSidesObjCommand\SidesObjCommand;

final class DeleteSidesCommandHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private SidesRepositoryInterface $sidesRepositoryInterface,
    ) {}

    public function handler(SidesObjCommand $sidesObjCommand): ?int
    {
        $sides = $sidesObjCommand->getSides();
        $successfully_delete = $this->sidesRepositoryInterface->delete($sides);

        return $successfully_delete['delete'];
    }
}
