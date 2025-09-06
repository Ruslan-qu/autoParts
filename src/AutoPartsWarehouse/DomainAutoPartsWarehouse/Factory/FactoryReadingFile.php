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
    public static function choiceReadingFile(AutoPartsFile $autoPartsFile): ?array
    {
        $input_errors = new InputErrorsAutoPartsWarehouse;
        $input_errors->emptyData($autoPartsFile->getFileSave());
        $arr_file = [];
        if ($autoPartsFile->getFileSave()->getClientOriginalExtension() == 'xlsx') {

            $readingFileXLSX = new ReadingFileXLSX;
            $arr_file = $readingFileXLSX->reading($autoPartsFile);
        } elseif ($autoPartsFile->getFileSave()->getClientOriginalExtension() == 'xml') {

            $readingFileXML = new ReadingFileXML;
            $arr_file = $readingFileXML->reading($autoPartsFile);
        } elseif ($autoPartsFile->getFileSave()->getClientOriginalExtension() == 'csv') {

            $readingFileCSV = new ReadingFileCSV;
            $arr_file = $readingFileCSV->reading($autoPartsFile);
        } elseif ($autoPartsFile->getFileSave()->getClientOriginalExtension() == 'ods') {

            $readingFileODS = new ReadingFileODS;
            $arr_file = $readingFileODS->reading($autoPartsFile);
        }

        $input_errors->determiningFileExtension();

        return $arr_file;
    }
}
