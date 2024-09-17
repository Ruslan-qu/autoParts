<?php

namespace App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers;

use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\OriginalRooms;

interface  OriginalRoomsRepositoryInterface
{
    public function save(OriginalRooms $originalRooms): array;
}
