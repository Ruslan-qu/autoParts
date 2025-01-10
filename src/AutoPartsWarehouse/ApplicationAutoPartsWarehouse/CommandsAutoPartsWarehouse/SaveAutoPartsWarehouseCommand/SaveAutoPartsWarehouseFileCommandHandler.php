<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\SaveAutoPartsWarehouseCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\AutoPartsWarehouseRepositoryInterface;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DTOCommands\DTOAutoPartsWarehouseCommand\ArrAutoPartsWarehouseCommand;

final class SaveAutoPartsWarehouseFileCommandHandler
{

    public function __construct(
        private InputErrorsAutoPartsWarehouse $inputErrorsAutoPartsWarehouse,
        private AutoPartsWarehouseRepositoryInterface $autoPartsWarehouseRepositoryInterface,

    ) {}

    public function handler(ArrAutoPartsWarehouseCommand $arrAutoPartsWarehouseCommand): ?string
    {

        $count_key = 0;
        foreach ($arrAutoPartsWarehouseCommand->getArrAutoPartsData() as $key => $value) {

            $count_key++;
            $quantity = $value['auto_parts_data']->getQuantity();
            $price = $value['auto_parts_data']->getPrice();
            $part_number = $value['auto_parts_data']->getIdDetails();
            $counterparty = $value['auto_parts_data']->getIdCounterparty();
            $date_receipt_auto_parts_warehouse = $value['auto_parts_data']->getDateReceiptAutoPartsWarehouse();
            $payment_method = $value['auto_parts_data']->getIdPaymentMethod();

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

            $autoPartsWarehouse = new AutoPartsWarehouse;
            $autoPartsWarehouse->setQuantity($quantity);
            $autoPartsWarehouse->setPrice($price);
            $autoPartsWarehouse->setSales(0);
            $autoPartsWarehouse->setIdCounterparty($counterparty);
            $autoPartsWarehouse->setIdDetails($part_number);
            $autoPartsWarehouse->setDateReceiptAutoPartsWarehouse($date_receipt_auto_parts_warehouse);
            $autoPartsWarehouse->setIdPaymentMethod($payment_method);

            $entityManager = $this->autoPartsWarehouseRepositoryInterface->persistData($autoPartsWarehouse);
        }


        $successfully_save = $this->autoPartsWarehouseRepositoryInterface->flushData($entityManager, $autoPartsWarehouse, $count_key);

        return $successfully_save['save'];
    }
}
