<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryAxles\DeleteAxlesQuery;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\ApplicationPartNumbers\QueryAxles\DTOQuery\DTOAxlesQuery\AxlesQuery;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\AxlesRepositoryInterface;

final class FindAxlesQueryHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private AxlesRepositoryInterface $axlesRepositoryInterface
    ) {}

    public function handler(AxlesQuery $axlesQuery): ?array
    {
        $id = $axlesQuery->getId();
        $this->inputErrorsPartNumbers->emptyData($id);

        $find_axles['axles'] = $this->axlesRepositoryInterface->findAxles($id);
        $this->inputErrorsPartNumbers->emptyEntity($find_axles);

        return $find_axles;
    }
}
