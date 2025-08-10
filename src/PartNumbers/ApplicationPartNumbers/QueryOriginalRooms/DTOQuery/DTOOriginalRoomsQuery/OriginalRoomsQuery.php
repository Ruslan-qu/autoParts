<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryOriginalRooms\DTOQuery\DTOOriginalRoomsQuery;

use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\PartNumbers\ApplicationPartNumbers\QueryOriginalRooms\DTOQuery\DTOOriginalRoomsQuery\MapOriginalRoomsQuery;

final class OriginalRoomsQuery extends MapOriginalRoomsQuery
{
    protected ?int $id = null;

    protected ?string $original_number = null;

    protected ?string $original_manufacturer = null;

    protected ?Participant $id_participant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOriginalNumber(): ?string
    {
        return $this->original_number;
    }

    public function getOriginalManufacturer(): ?string
    {
        return $this->original_manufacturer;
    }

    public function getIdParticipant(): ?Participant
    {
        return $this->id_participant;
    }
}
