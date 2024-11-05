<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOOriginalRoomsCommand;

use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOOriginalRoomsCommand\MapOriginalRoomsCommand;


final class OriginalRoomsCommand extends MapOriginalRoomsCommand
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
