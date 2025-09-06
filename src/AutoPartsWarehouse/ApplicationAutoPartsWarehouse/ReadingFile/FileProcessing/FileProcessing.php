<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingFile\FileProcessing;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\Factory\FactoryReadingFile;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingFile\DTOAutoPartsFile\AutoPartsFile;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;

class FileProcessing
{
    public static function processing(?array $file)
    {
        $input_errors = new InputErrorsAutoPartsWarehouse;
        $input_errors->emptyData($file);

        return FactoryReadingFile::choiceReadingFile(new AutoPartsFile($file));
    }
}
