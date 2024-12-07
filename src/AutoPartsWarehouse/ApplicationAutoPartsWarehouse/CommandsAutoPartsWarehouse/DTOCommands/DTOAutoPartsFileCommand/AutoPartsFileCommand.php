<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DTOCommands\DTOAutoPartsFileCommand;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DTOCommands\DTOAutoPartsFileCommand\MapAutoPartsFileCommand;

final class AutoPartsFileCommand extends MapAutoPartsFileCommand

{
    protected ?UploadedFile $file_save = null;

    public function getFileSave(): ?UploadedFile
    {
        return $this->file_save;
    }
}
