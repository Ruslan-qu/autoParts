<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryReplacingOriginalNumbers\EditReplacingOriginalNumbersQuery;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\ReplacingOriginalNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\ReplacingOriginalNumbersRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryReplacingOriginalNumbers\DTOQuery\DTOReplacingOriginalNumbersQuery\ReplacingOriginalNumbersQuery;

final class FindOneByIdReplacingOriginalNumbersQueryHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private ReplacingOriginalNumbersRepositoryInterface $replacingOriginalNumbersRepositoryInterface
    ) {}

    public function handler(ReplacingOriginalNumbersQuery $replacingOriginalNumbersQuery): ?ReplacingOriginalNumbers
    {
        $id = $replacingOriginalNumbersQuery->getId();
        $this->inputErrorsPartNumbers->emptyData($id);
        $participant = $replacingOriginalNumbersQuery->getIdParticipant();
        $this->inputErrorsPartNumbers->userIsNotIdentified($participant);

        $edit_replacing_original_number = $this->replacingOriginalNumbersRepositoryInterface->findOneByIdReplacingOriginalNumbers($id, $participant);
        $this->inputErrorsPartNumbers->emptyEntity($edit_replacing_original_number);

        return $edit_replacing_original_number;
    }
}
