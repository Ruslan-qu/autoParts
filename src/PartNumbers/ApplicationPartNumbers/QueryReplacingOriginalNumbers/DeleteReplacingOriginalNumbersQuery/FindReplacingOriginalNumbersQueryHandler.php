<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryReplacingOriginalNumbers\DeleteReplacingOriginalNumbersQuery;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\ReplacingOriginalNumbersRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryReplacingOriginalNumbers\DTOQuery\DTOReplacingOriginalNumbersQuery\ReplacingOriginalNumbersQuery;

final class FindReplacingOriginalNumbersQueryHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private ReplacingOriginalNumbersRepositoryInterface $replacingOriginalNumbersRepositoryInterface
    ) {}

    public function handler(ReplacingOriginalNumbersQuery $replacingOriginalNumbersQuery): ?array
    {
        $id = $replacingOriginalNumbersQuery->getId();
        $this->inputErrorsPartNumbers->emptyData($id);

        $find_replacing_original_numbers['replacing_original_numbers'] = $this->replacingOriginalNumbersRepositoryInterface->findReplacingOriginalNumbers($id);
        $this->inputErrorsPartNumbers->emptyEntity($find_replacing_original_numbers);

        return $find_replacing_original_numbers;
    }
}
