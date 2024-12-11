<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\DTOAutoPartsFile;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\DTOAutoPartsFile\MapAutoPartsFile;

final class AutoPartsFile extends MapAutoPartsFile

{
    protected ?UploadedFile $file_save = null;

    //protected ?string $file_extension = null;

    public function getFileSave(): ?UploadedFile
    {
        return $this->file_save;
    }

    /* public function getFileExtension(): ?string
    {
        return $this->file_extension;
    }*/
}
