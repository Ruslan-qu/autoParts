<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\DTOQuery\DTOPartNameQuery;

use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\DTOQuery\DTOPartNameQuery\MapPartNameQuery;

final class PartNameQuery extends MapPartNameQuery
{
    protected ?int $id = null;

    protected ?string $part_name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPartName(): ?string
    {
        return $this->part_name;
    }
}
