<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\SearchPartNumbersQuery;

use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\OriginalRoomsRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\DTOQuery\DTOOriginalRoomsQuery\OriginalRoomsQuery;

final class FindOneByOriginalRoomsQueryHandler
{
    private $original_rooms_repository_interface;

    public function __construct(
        OriginalRoomsRepositoryInterface $originalRoomsRepositoryInterface
    ) {
        $this->original_rooms_repository_interface = $originalRoomsRepositoryInterface;
    }

    public function handler(OriginalRoomsQuery $originalRoomsQuery): ?array
    {

        $original_number = strtolower(preg_replace(
            '#\s#',
            '',
            $originalRoomsQuery->getOriginalNumber()
        ));

        $findOneByOriginalRooms = $this->original_rooms_repository_interface->findOneByOriginalRooms($original_number);

        $arr_original_rooms = ['id_original_number' => $findOneByOriginalRooms];

        return $arr_original_rooms;
    }
}
