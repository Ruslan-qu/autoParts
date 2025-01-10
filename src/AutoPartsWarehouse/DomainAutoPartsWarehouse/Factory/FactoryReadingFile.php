<?php

namespace App\AutoPartsWarehouse\DomainAutoPartsWarehouse\Factory;

use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingFile\ReadingFileCSV\ReadingFileCSV;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingFile\ReadingFileODS\ReadingFileODS;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingFile\ReadingFileXML\ReadingFileXML;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingFile\DTOAutoPartsFile\AutoPartsFile;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingFile\ReadingFileXLSX\ReadingFileXLSX;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;

class FactoryReadingFile
{
    public static function choiceReadingFile(AutoPartsFile $autoPartsFile)
    {
        $input_errors = new InputErrorsAutoPartsWarehouse;
        $input_errors->emptyData($autoPartsFile->getFileSave());

        if ($autoPartsFile->getFileSave()->getClientOriginalExtension() == 'xlsx') {

            $readingFileXLSX = new ReadingFileXLSX;
            return $readingFileXLSX->reading($autoPartsFile);
        } elseif ($autoPartsFile->getFileSave()->getClientOriginalExtension() == 'xml') {

            $readingFileXML = new ReadingFileXML;
            return $readingFileXML->reading($autoPartsFile);
        } elseif ($autoPartsFile->getFileSave()->getClientOriginalExtension() == 'csv') {

            $readingFileCSV = new ReadingFileCSV;
            return $readingFileCSV->reading($autoPartsFile);
        } elseif ($autoPartsFile->getFileSave()->getClientOriginalExtension() == 'ods') {

            $readingFileODS = new ReadingFileODS;
            return $readingFileODS->reading($autoPartsFile);
        }

        $input_errors->determiningFileExtension();
    }
}
