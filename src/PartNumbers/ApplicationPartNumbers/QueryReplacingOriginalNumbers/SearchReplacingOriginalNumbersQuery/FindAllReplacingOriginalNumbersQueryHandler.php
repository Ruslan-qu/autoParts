<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryReplacingOriginalNumbers\SearchReplacingOriginalNumbersQuery;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\ReplacingOriginalNumbersRepositoryInterface;

final class FindAllReplacingOriginalNumbersQueryHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private ReplacingOriginalNumbersRepositoryInterface $replacingOriginalNumbersRepositoryInterface
    ) {}

    public function handler(): ?array
    {

        $findAllReplacingOriginalNumbers = $this->replacingOriginalNumbersRepositoryInterface->findAllReplacingOriginalNumbers();

        return $findAllReplacingOriginalNumbers;
    }
}
