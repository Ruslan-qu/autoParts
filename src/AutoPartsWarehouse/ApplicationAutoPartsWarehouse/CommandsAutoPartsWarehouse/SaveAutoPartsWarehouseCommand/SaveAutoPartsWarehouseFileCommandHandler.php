<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\SaveAutoPartsWarehouseCommand;

use Doctrine\Common\Collections\Collection as Doctrine;
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
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DTOCommands\DTOAutoPartsWarehouseCommand\ArrAutoPartsWarehouseCommand;

final class SaveAutoPartsWarehouseFileCommandHandler
{

    public function __construct(
        private InputErrorsAutoPartsWarehouse $inputErrorsAutoPartsWarehouse,
        private AutoPartsWarehouseRepositoryInterface $autoPartsWarehouseRepositoryInterface,
        private AutoPartsWarehouse $autoPartsWarehouse
    ) {}

    public function handler(ArrAutoPartsWarehouseCommand $arrAutoPartsWarehouseCommand): ?int
    {
        $q = new Doctrine('autoPartsWarehouse');
        foreach ($arrAutoPartsWarehouseCommand->getArrAutoPartsData() as $key => $value) {

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

            $this->autoPartsWarehouse->setQuantity($quantity);
            $this->autoPartsWarehouse->setPrice($price);
            $this->autoPartsWarehouse->setSales(0);
            $this->autoPartsWarehouse->setIdCounterparty($counterparty);
            $this->autoPartsWarehouse->setIdDetails($part_number);
            $this->autoPartsWarehouse->setDateReceiptAutoPartsWarehouse($date_receipt_auto_parts_warehouse);
            $this->autoPartsWarehouse->setIdPaymentMethod($payment_method);

            // $entityManager = $this->autoPartsWarehouseRepositoryInterface->persistData($this->autoPartsWarehouse);
            $q->add($this->autoPartsWarehouse . $key);
        }

        dd($q);
        $successfully_save = $this->autoPartsWarehouseRepositoryInterface->flushData($entityManager);

        $id = $successfully_save['save'];
        return $id;
    }
}
