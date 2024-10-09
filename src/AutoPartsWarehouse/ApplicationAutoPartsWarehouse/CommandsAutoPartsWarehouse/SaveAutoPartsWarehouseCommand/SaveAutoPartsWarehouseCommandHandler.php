<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\SaveAutoPartsWarehouseCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\AutoPartsWarehouseRepositoryInterface;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DTOCommands\DTOAutoPartsWarehouseCommand\AutoPartsWarehouseCommand;

final class SaveAutoPartsWarehouseCommandHandler
{

    public function __construct(
        private AutoPartsWarehouseRepositoryInterface $autoPartsWarehouseRepositoryInterface,
        private AutoPartsWarehouse $autoPartsWarehouse
    ) {}

    public function handler(AutoPartsWarehouseCommand $autoPartsWarehouseCommand): ?int
    {
        // dd($createAutoPartsWarehouseCommand);
        $quantity = $autoPartsWarehouseCommand->getQuantity();

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $input = [
            'quantity_error' => [
                'NotBlank' => $quantity,
                'Type' => $quantity,
                'Regex' => $quantity,
            ]
        ];

        $constraint = new Collection([
            'quantity_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма Количество не может быть 
                    пустой'
                ),
                'Type' => new Type('int'),
                'Regex' => new Regex(
                    pattern: '/^\d+$/',
                    message: 'Форма Количество содержит 
                    недопустимые символы'
                )
            ])
        ]);

        $data_errors_auto_parts_warehouse = [];
        foreach ($validator->validate($input, $constraint) as $key => $value_error) {

            $data_errors_auto_parts_warehouse[$key] = [
                $value_error->getPropertyPath() => $value_error->getMessage()
            ];
        }


        $price = $autoPartsWarehouseCommand->getPrice();
        $input = [
            'price_error' => [
                'NotBlank' => $price,
                'Type' => $price,
                'Regex' => $price,
            ]
        ];

        $constraint = new Collection([
            'price_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма Цена не может быть 
                    пустой'
                ),
                'Type' => new Type('int'),
                'Regex' => new Regex(
                    pattern: '/^[\d]+[\.,]?[\d]*$/',
                    message: 'Форма Цена содержит 
                    недопустимые символы'
                )
            ])
        ]);
        $data_errors_price = [];
        foreach ($validator->validate($input, $constraint) as $key => $value_error) {

            $data_errors_price[$key] = [
                $value_error->getPropertyPath() => $value_error->getMessage()
            ];
        }
        $data_errors_auto_parts_warehouse = array_merge($data_errors_auto_parts_warehouse, $data_errors_price);


        $counterparty = $autoPartsWarehouseCommand->getIdCounterparty();


        $part_number = $autoPartsWarehouseCommand->getIdDetails();
        $input = [
            'NotBlank' => $part_number,
        ];

        $constraint = new Collection([
            'NotBlank' => new NotBlank(
                message: 'Форма № Детали не может быть 
                    пустой'
            )
        ]);
        $data_errors_part_number = [];
        foreach ($validator->validate($input, $constraint) as $key => $value_error) {

            $data_errors_part_number[$key] = [
                $value_error->getPropertyPath() => $value_error->getMessage()
            ];
        }
        $data_errors_auto_parts_warehouse = array_merge($data_errors_auto_parts_warehouse, $data_errors_part_number);


        $manufacturer = $autoPartsWarehouseCommand->getIdManufacturer();


        $date_receipt_auto_parts_warehouse = $autoPartsWarehouseCommand->getDateReceiptAutoPartsWarehouse();
        $input = [
            'NotBlank' => $date_receipt_auto_parts_warehouse,
        ];

        $constraint = new Collection([
            'NotBlank' => new NotBlank(
                message: 'Форма Дата прихода не может быть 
                    пустой'
            )
        ]);
        $data_errors_date_receipt_auto_parts_warehouse = [];
        foreach ($validator->validate($input, $constraint) as $key => $value_error) {

            $data_errors_date_receipt_auto_parts_warehouse[$key] = [
                $value_error->getPropertyPath() => $value_error->getMessage()
            ];
        }
        $data_errors_auto_parts_warehouse = array_merge($data_errors_auto_parts_warehouse, $data_errors_date_receipt_auto_parts_warehouse);


        $payment_method = $autoPartsWarehouseCommand->getIdPaymentMethod();
        $input = [
            'NotBlank' => $payment_method,
        ];

        $constraint = new Collection([
            'NotBlank' => new NotBlank(
                message: 'Форма Способ оплаты не может быть 
                    пустой'
            )
        ]);
        $data_errors_payment_method = [];
        foreach ($validator->validate($input, $constraint) as $key => $value_error) {

            $data_errors_payment_method[$key] = [
                $value_error->getPropertyPath() => $value_error->getMessage()
            ];
        }
        $data_errors_auto_parts_warehouse = array_merge($data_errors_auto_parts_warehouse, $data_errors_payment_method);

        if (!empty($data_errors_auto_parts_warehouse)) {

            $json_arr_data_errors = json_encode($data_errors_auto_parts_warehouse, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        $id = $autoPartsWarehouseCommand->getId();

        if (!empty($id)) {

            return null;
        }

        $this->autoPartsWarehouse->setQuantity($quantity);
        $this->autoPartsWarehouse->setPrice($price);
        $this->autoPartsWarehouse->setIdCounterparty($counterparty);
        $this->autoPartsWarehouse->setIdDetails($part_number);
        $this->autoPartsWarehouse->setIdManufacturer($manufacturer);
        $this->autoPartsWarehouse->setDateReceiptAutoPartsWarehouse($date_receipt_auto_parts_warehouse);
        $this->autoPartsWarehouse->setIdPaymentMethod($payment_method);

        $successfully_save = $this->autoPartsWarehouseRepositoryInterface->save($this->autoPartsWarehouse);

        $id = $successfully_save['save'];
        return $id;
    }
}
