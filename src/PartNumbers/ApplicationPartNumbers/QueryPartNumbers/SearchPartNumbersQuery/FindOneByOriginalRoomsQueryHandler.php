<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\SearchPartNumbersQuery;

use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\OriginalRoomsRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryPartNumbers\DTOQuery\DTOOriginalRoomsQuery\OriginalRoomsQuery;

final class FindOneByOriginalRoomsQueryHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private OriginalRoomsRepositoryInterface $originalRoomsRepositoryInterface
    ) {}

    public function handler(OriginalRoomsQuery $originalRoomsQuery): ?array
    {

        $original_number = strtolower(preg_replace(
            '#\s#',
            '',
            $originalRoomsQuery->getOriginalNumber()
        ));
        $this->inputErrorsPartNumbers->emptyData($original_number);

        $findOneByOriginalRooms = $this->originalRoomsRepositoryInterface->findOneByOriginalRooms($original_number);
        $this->inputErrorsPartNumbers->emptyEntity($findOneByOriginalRooms);

        $arr_original_rooms = ['id_original_number' => $findOneByOriginalRooms];

        return $arr_original_rooms;
    }
}
