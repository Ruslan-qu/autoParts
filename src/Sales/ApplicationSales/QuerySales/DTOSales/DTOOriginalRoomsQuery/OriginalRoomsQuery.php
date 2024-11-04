<?php

namespace App\Sales\ApplicationSales\QuerySales\DTOSales\DTOOriginalRoomsQuery;


use App\Sales\ApplicationSales\QuerySales\DTOSales\DTOOriginalRoomsQuery\MapOriginalRoomsQuery;

final class OriginalRoomsQuery extends MapOriginalRoomsQuery
{
    protected ?int $id = null;

    protected ?string $original_number = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOriginalNumber(): ?string
    {
        return $this->original_number;
    }
}
