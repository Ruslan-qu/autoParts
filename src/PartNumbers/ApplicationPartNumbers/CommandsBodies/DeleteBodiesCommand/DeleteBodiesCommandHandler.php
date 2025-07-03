<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsBodies\DeleteBodiesCommand;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\BodiesRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsBodies\DTOCommands\DTOBodiesObjCommand\BodiesObjCommand;

final class DeleteBodiesCommandHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private BodiesRepositoryInterface $bodiesRepositoryInterface,
    ) {}

    public function handler(BodiesObjCommand $bodiesObjCommand): ?int
    {
        $bodies = $bodiesObjCommand->getBodies();
        $successfully_delete = $this->bodiesRepositoryInterface->delete($bodies);

        return $successfully_delete['delete'];
    }
}
