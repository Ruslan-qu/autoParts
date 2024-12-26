<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\SaveAutoPartsWarehouseCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ReadingFile\DTOAutoPartsFile\AutoPartsFile;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\AutoPartsWarehouseRepositoryInterface;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DTOCommands\DTOAutoPartsFileCommand\AutoPartsFileCommand;

final class SaveAutoPartsWarehouseFileCommandHandler
{

    public function __construct(
        private InputErrorsAutoPartsWarehouse $inputErrorsAutoPartsWarehouse,
        private AutoPartsWarehouseRepositoryInterface $autoPartsWarehouseRepositoryInterface,
        private AutoPartsWarehouse $autoPartsWarehouse
    ) {}

    public function handler(AutoPartsFile $autoPartsFile): ?int
    {

        $file = $autoPartsFile->getFileSave();

        dd($file);
        /* Подключаем валидацию и прописываем условия валидации */
        $validator = Validation::createValidator();
        $input = [
            'file_error' => [
                'NotBlank' => $file,
                'File' => $file,
            ],
        ];

        $constraint = new Collection([
            'quantity_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма Количество не может быть 
                    пустой'
                ),
                'File' => new File(
                    maxSize: '64M',
                    maxSizeMessage: 'Максимальный размер файла не должен превышать 64м',
                    extensions: [
                        'xlsx',
                        'xml',
                        'csv',
                        'ods'
                    ],
                    extensionsMessage: 'Указано неверное разрешение файла
                    разрешение должно быть XLSX(Excel), XML, CSV, ODS'
                )
            ]),
        ]);

        $errors_validate = $validator->validate($input, $constraint);
        $this->inputErrorsAutoPartsWarehouse->errorValidate($errors_validate);



        $this->autoPartsWarehouse->setQuantity($quantity);
        $this->autoPartsWarehouse->setPrice($price);
        $this->autoPartsWarehouse->setSales(0);
        $this->autoPartsWarehouse->setIdCounterparty($counterparty);
        $this->autoPartsWarehouse->setIdDetails($part_number);
        $this->autoPartsWarehouse->setDateReceiptAutoPartsWarehouse($date_receipt_auto_parts_warehouse);
        $this->autoPartsWarehouse->setIdPaymentMethod($payment_method);

        $successfully_save = $this->autoPartsWarehouseRepositoryInterface->save($this->autoPartsWarehouse);

        $id = $successfully_save['save'];
        return $id;
    }
}
