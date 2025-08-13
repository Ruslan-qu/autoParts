<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryReplacingOriginalNumbers\SearchReplacingOriginalNumbersQuery;

use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\ReplacingOriginalNumbersRepositoryInterface;

final class FindByReplacingOriginalNumbersQueryHandler
{

    public function __construct(
        private ReplacingOriginalNumbersRepositoryInterface $replacingOriginalNumbersRepositoryInterface
    ) {}

    public function handler(): ?array
    {

        $findAllAvailability = $this->replacingOriginalNumbersRepositoryInterface
            ->findAllRoomsRepository();

        return $findAllAvailability;
    }
}
