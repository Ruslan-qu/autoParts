<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\SaveAutoPartsWarehouseCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
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

    public function handler(AutoPartsFileCommand $autoPartsFileCommand): ?int
    {

        $file = $autoPartsFileCommand->getFileSave();

        dd($file);
        /* Подключаем валидацию и прописываем условия валидации */
        $validator = Validation::createValidator();
        $input = [
            'quantity_error' => [
                'NotBlank' => $quantity,
                'Regex' => $quantity,
            ],
            'price_error' => [
                'NotBlank' => $price,
                'Regex' => $price,
            ],
            'part_number_error' => $part_number,
            'date_receipt_auto_parts_warehouse_error' => $date_receipt_auto_parts_warehouse,
            'payment_method_error' => $payment_method,
        ];

        $constraint = new Collection([
            'quantity_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма Количество не может быть 
                    пустой'
                ),
                'Regex' => new Regex(
                    pattern: '/^\d+$/',
                    message: 'Форма Количество содержит 
                    недопустимые символы'
                )
            ]),
            'price_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма Цена не может быть 
                    пустой'
                ),
                'Regex' => new Regex(
                    pattern: '/^[\d]+[\.,]?[\d]*$/',
                    message: 'Форма Цена содержит 
                    недопустимые символы'
                )
            ]),
            'part_number_error' => new NotBlank(
                message: 'Форма № Детали не может быть 
                    пустой'
            ),
            'date_receipt_auto_parts_warehouse_error' => new NotBlank(
                message: 'Форма Дата прихода не может быть 
                    пустой'
            ),
            'payment_method_error' => new NotBlank(
                message: 'Форма Способ оплаты не может быть 
                    пустой'
            )
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
