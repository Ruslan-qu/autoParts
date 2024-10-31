<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\EditCartAutoPartsCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\AutoPartsSoldRepositoryInterface;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DTOCommands\DTOAutoPartsSoldCommand\AutoPartsSoldCommand;

final class EditCartAutoPartsCommandHandler
{

    public function __construct(
        private AutoPartsSoldRepositoryInterface $autoPartsSoldRepositoryInterface
    ) {}

    public function handler(AutoPartsSoldCommand $autoPartsSoldCommand): ?int
    {

        /* Подключаем валидацию и прописываем условие валидации */
        $validator = Validation::createValidator();

        $id = $autoPartsSoldCommand->getId();
        $arr_auto_parts_sold['id'] = $id;

        $find_auto_parts_sold = $this->autoPartsSoldRepositoryInterface->findAutoPartsSold($id);
        $find_auto_parts_sold_quantity_sold = $find_auto_parts_sold->getQuantitySold();

        $auto_parts_warehouse = $autoPartsSoldCommand->getIdAutoPartsWarehouse();
        $arr_auto_parts_sold['id_auto_parts_warehouse'] = $auto_parts_warehouse;
        $quantity_auto_parts_warehouse = $auto_parts_warehouse->getQuantity();
        $quantity_sold_auto_parts_warehouse = $auto_parts_warehouse->getQuantitySold();
        $subtraction_quantity_sold_auto_parts_warehouse = ($quantity_sold_auto_parts_warehouse - $find_auto_parts_sold_quantity_sold);
        $subtraction_quantity_auto_parts_warehouse = ($quantity_auto_parts_warehouse - $subtraction_quantity_sold_auto_parts_warehouse);

        $quantity_sold = $autoPartsSoldCommand->getQuantitySold();
        $arr_auto_parts_sold['quantity_sold'] = $quantity_sold;
        $sum_quantity_sold_auto_parts_warehouse = ($subtraction_quantity_sold_auto_parts_warehouse + $quantity_sold);

        $price_sold = $autoPartsSoldCommand->getPriceSold();
        $arr_auto_parts_sold['price_sold'] = $price_sold;
        $date_sold = $autoPartsSoldCommand->getDateSold();
        $arr_auto_parts_sold['date_sold'] = $date_sold;
        $input = [
            'quantity_sold_error' => [
                'NotBlank' => $quantity_sold,
                'Type' => $quantity_sold,
                'Regex' => $quantity_sold,
                'Range' => $quantity_sold,
            ],
            'quantity_sold_auto_parts_warehouse_error' => $sum_quantity_sold_auto_parts_warehouse,
            'price_sold_error' => [
                'NotBlank' => $price_sold,
                'Type' => $price_sold,
                'Regex' => $price_sold,
            ],
            'date_sold' => $date_sold
        ];

        $constraint = new Collection([
            'quantity_sold_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма Количество не может быть 
                    пустой'
                ),
                'Type' => new Type('int'),
                'Regex' => new Regex(
                    pattern: '/^\d+$/',
                    message: 'Форма Количество содержит 
                    недопустимые символы'
                ),
                'Range' => new Range(
                    min: 1,
                    max: $subtraction_quantity_auto_parts_warehouse,
                    notInRangeMessage: 'Форма Количество не может быть 
                    меньше и не больше количества автодеталей на складе',
                )
            ]),
            'quantity_sold_auto_parts_warehouse_error' => new Range(
                min: 1,
                max: $quantity_auto_parts_warehouse,
                notInRangeMessage: 'Форма Количество не может быть 
                    меньше и не больше количества автодеталей на складе',
            ),
            'price_sold_error' => new Collection([
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
            ]),
            'date_sold' => new NotBlank(
                message: 'Форма Дата продажи не может быть 
                    пустой'
            )
        ]);

        $errors = $validator->validate($input, $constraint);

        if ($errors->count()) {
            $validator_errors = [];
            foreach ($validator->validate($input, $constraint) as $key => $value_error) {

                $validator_errors[$key] = [
                    $value_error->getPropertyPath() => $value_error->getMessage()
                ];
            }
            $json_data_errors = json_encode($validator_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_data_errors);
        }



        $auto_parts_warehouse->setQuantitySold($sum_quantity_sold_auto_parts_warehouse);
        $find_auto_parts_sold->setQuantitySold($quantity_sold);
        $find_auto_parts_sold->setPriceSold($price_sold);
        $find_auto_parts_sold->setDateSold($date_sold);

        $successfully_save = $this->autoPartsSoldRepositoryInterface->edit($arr_auto_parts_sold);

        $id = $successfully_save['edit'];
        return $id;
    }
}
