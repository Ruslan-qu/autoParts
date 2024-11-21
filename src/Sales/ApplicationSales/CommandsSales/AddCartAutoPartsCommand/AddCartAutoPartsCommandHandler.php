<?php

namespace App\Sales\ApplicationSales\CommandsSales\AddCartAutoPartsCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\Sales\DomainSales\DomainModelSales\AutoPartsSold;
use App\Sales\ApplicationSales\ErrorsSales\InputErrorsSales;
use App\Sales\DomainSales\RepositoryInterfaceSales\AutoPartsSoldRepositoryInterface;
use App\Sales\ApplicationSales\CommandsSales\DTOAutoPartsSoldCommand\AutoPartsSoldCommand;

final class AddCartAutoPartsCommandHandler
{

    public function __construct(
        private InputErrorsSales $InputErrorsSales,
        private AutoPartsSoldRepositoryInterface $autoPartsSoldRepositoryInterface,
        private AutoPartsSold $autoPartsSold
    ) {}

    public function handler(AutoPartsSoldCommand $autoPartsSoldCommand): ?int
    {

        /* Подключаем валидацию и прописываем условие валидации */
        $validator = Validation::createValidator();

        $auto_parts_warehouse = $autoPartsSoldCommand->getIdAutoPartsWarehouse();

        $quantity_auto_parts_warehouse = $auto_parts_warehouse->getQuantity();
        $quantity_sold_auto_parts_warehouse = $auto_parts_warehouse->getQuantitySold();

        $subtraction_quantity_auto_parts_warehouse = ($quantity_auto_parts_warehouse - $quantity_sold_auto_parts_warehouse);
        $quantity_sold = $autoPartsSoldCommand->getQuantitySold();

        $sum_quantity_sold_auto_parts_warehouse = ($quantity_sold_auto_parts_warehouse + $quantity_sold);

        $price_sold = $autoPartsSoldCommand->getPriceSold();

        $date_sold = $autoPartsSoldCommand->getDateSold();

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

        $errors_validate = $validator->validate($input, $constraint);
        $this->InputErrorsSales->errorValidate($errors_validate);

        $auto_parts_warehouse->setQuantitySold($sum_quantity_sold_auto_parts_warehouse);
        $this->autoPartsSold->setIdAutoPartsWarehouse($auto_parts_warehouse);
        $this->autoPartsSold->setQuantitySold($quantity_sold);
        $this->autoPartsSold->setPriceSold($price_sold);
        $this->autoPartsSold->setDateSold($date_sold);
        $this->autoPartsSold->setSoldStatus(false);

        $successfully_save = $this->autoPartsSoldRepositoryInterface->save($this->autoPartsSold);

        $id = $successfully_save['save'];
        return $id;
    }
}
