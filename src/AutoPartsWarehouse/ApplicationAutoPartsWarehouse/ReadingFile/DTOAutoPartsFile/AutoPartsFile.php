<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingFile\DTOAutoPartsFile;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingFile\DTOAutoPartsFile\MapAutoPartsFile;

final class AutoPartsFile extends MapAutoPartsFile

{
    protected ?UploadedFile $file_save = null;

    public function getFileSave(): ?UploadedFile
    {
        return $this->file_save;
    }
}
