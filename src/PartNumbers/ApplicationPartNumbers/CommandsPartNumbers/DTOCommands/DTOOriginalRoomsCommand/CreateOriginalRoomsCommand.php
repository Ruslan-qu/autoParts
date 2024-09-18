<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOOriginalRoomsCommand;

use App\PartNumbers\ApplicationPartNumbers\CommandsPartNumbers\DTOCommands\DTOOriginalRoomsCommand\OriginalRoomsCommand;


final class CreateOriginalRoomsCommand extends OriginalRoomsCommand
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
