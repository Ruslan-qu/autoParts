<?php

namespace App\AutoPartsWarehouse\DomainAutoPartsWarehouse\Factory;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\DTOAutoPartsFile\AutoPartsFile;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingFile\ReadingFileXLSX\ReadingFileXLSX;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;

class FactoryReadingFile
{
    public static function choiceReadingFile(AutoPartsFile $autoPartsFile)
    {
        $input_errors = new InputErrorsAutoPartsWarehouse;
        $input_errors->emptyData($autoPartsFile);

        if ($autoPartsFile->getFileSave()->getClientOriginalExtension() == 'xlsx') {

            $readingFileXLSX = new ReadingFileXLSX;
            return $readingFileXLSX->reading($autoPartsFile);
        } elseif ($autoPartsFile->getFileSave()->getClientOriginalExtension() == 'xml') {
            # code...
        } elseif ($autoPartsFile->getFileSave()->getClientOriginalExtension() == 'csv') {
            # code...
        } elseif ($autoPartsFile->getFileSave()->getClientOriginalExtension() == 'ods') {
            # code...
        }

        $input_errors->determiningFileExtension();
    }
}
