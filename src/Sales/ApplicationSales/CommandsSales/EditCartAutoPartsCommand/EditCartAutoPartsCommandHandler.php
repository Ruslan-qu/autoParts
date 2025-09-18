<?php

namespace App\Sales\ApplicationSales\CommandsSales\EditCartAutoPartsCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\Sales\ApplicationSales\ErrorsSales\InputErrorsSales;
use App\Sales\DomainSales\RepositoryInterfaceSales\AutoPartsSoldRepositoryInterface;
use App\Sales\ApplicationSales\CommandsSales\DTOAutoPartsSoldCommand\AutoPartsSoldCommand;

final class EditCartAutoPartsCommandHandler
{

    public function __construct(
        private InputErrorsSales $inputErrorsSales,
        private AutoPartsSoldRepositoryInterface $autoPartsSoldRepositoryInterface
    ) {}

    public function handler(AutoPartsSoldCommand $autoPartsSoldCommand): ?int
    {

        $id = $autoPartsSoldCommand->getId();
        $this->inputErrorsSales->emptyData($id);

        $id_participant = $autoPartsSoldCommand->getIdParticipant();

        $auto_parts_sold = $this->autoPartsSoldRepositoryInterface->findOneByAutoPartsSold($id, $id_participant);
        $this->inputErrorsSales->emptyEntity($auto_parts_sold);
        $find_auto_parts_sold_quantity_sold = $auto_parts_sold->getQuantitySold();

        $auto_parts_warehouse = $autoPartsSoldCommand->getIdAutoPartsWarehouse();
        $quantity_auto_parts_warehouse = $auto_parts_warehouse->getQuantity();
        $quantity_sold_auto_parts_warehouse = $auto_parts_warehouse->getQuantitySold();
        $subtraction_quantity_sold_auto_parts_warehouse = ($quantity_sold_auto_parts_warehouse - $find_auto_parts_sold_quantity_sold);
        $subtraction_quantity_auto_parts_warehouse = ($quantity_auto_parts_warehouse - $subtraction_quantity_sold_auto_parts_warehouse);

        $quantity_sold = $autoPartsSoldCommand->getQuantitySold();
        $sum_quantity_sold_auto_parts_warehouse = ($subtraction_quantity_sold_auto_parts_warehouse + $quantity_sold);

        $price_sold = $autoPartsSoldCommand->getPriceSold();
        $date_sold = $autoPartsSoldCommand->getDateSold();

        /* Подключаем валидацию и прописываем условие валидации */
        $validator = Validation::createValidator();
        $input = [
            'quantity_sold_error' => [
                'NotBlank' => $quantity_sold,
                'Regex' => $quantity_sold,
                'Range' => $quantity_sold,
            ],
            'quantity_sold_auto_parts_warehouse_error' => $sum_quantity_sold_auto_parts_warehouse,
            'price_sold_error' => [
                'NotBlank' => $price_sold,
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
        $this->inputErrorsSales->errorValidate($errors_validate);

        $auto_parts_warehouse->setQuantitySold($sum_quantity_sold_auto_parts_warehouse);
        $auto_parts_sold->setQuantitySold($quantity_sold);
        $auto_parts_sold->setPriceSold($price_sold);
        $auto_parts_sold->setDateSold($date_sold);

        return $this->autoPartsSoldRepositoryInterface->edit($auto_parts_sold);
    }
}
