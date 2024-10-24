<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\AddCartAutoPartsCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsSold;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\AutoPartsSoldRepositoryInterface;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DTOCommands\DTOAutoPartsSoldCommand\AutoPartsSoldCommand;

final class AddCartAutoPartsCommandHandler
{

    public function __construct(
        private AutoPartsSoldRepositoryInterface $autoPartsSoldRepositoryInterface,
        private AutoPartsSold $autoPartsSoldCommand
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
        /*  $input = [
            'quantity_sold_error' => [
                'NotBlank' => $quantity_sold,
                'Type' => $quantity_sold,
                'Regex' => $quantity_sold,
                'Range' => $quantity_sold,
            ]
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
                    меньше {{ min }} и не больше {{ max }} количества автодеталей на складе',
                )
            ])
        ]);

        $data_errors_auto_parts_sold = [];
        foreach ($validator->validate($input, $constraint) as $key => $value_error) {

            $data_errors_auto_parts_sold[$key] = [
                $value_error->getPropertyPath() => $value_error->getMessage()
            ];
        }
*/

        $sum_quantity_sold_auto_parts_warehouse = ($quantity_sold_auto_parts_warehouse + $quantity_sold);
        /* $input = [
            'quantity_sold_auto_parts_warehouse_error' => $sum_quantity_sold_auto_parts_warehouse
        ];

        $constraint = new Collection([
            'quantity_sold_auto_parts_warehouse_error' => new Range(
                    min: 1,
                    max: $quantity_auto_parts_warehouse,
                    notInRangeMessage: 'Форма Количество не может быть 
                    меньше {{ min }} и не больше {{ max }} количества автодеталей на складе',
                )
        ]);

        $data_errors_auto_parts_sold = [];
        foreach ($validator->validate($input, $constraint) as $key => $value_error) {

            $data_errors_auto_parts_sold[$key] = [
                $value_error->getPropertyPath() => $value_error->getMessage()
            ];
        }

*/

        $price_sold = $autoPartsSoldCommand->getPriceSold();
        /*    $input = [
            'price_sold_error' => [
                'NotBlank' => $price_sold,
                'Type' => $price_sold,
                'Regex' => $price_sold,
            ]
        ];

        $constraint = new Collection([
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
            ])
        ]);
        $data_errors_price_sold = [];
        foreach ($validator->validate($input, $constraint) as $key => $value_error) {

            $data_errors_price_sold[$key] = [
                $value_error->getPropertyPath() => $value_error->getMessage()
            ];
        }
        $data_errors_auto_parts_sold = array_merge($data_errors_auto_parts_sold, $data_errors_price_sold);
*/


        $date_sold = $autoPartsSoldCommand->getDateSold();
        /* $input = [
            'date_sold' => $date_sold
        ];

        $constraint = new Collection([
            'date_sold' => new NotBlank(
                message: 'Форма Дата продажи не может быть 
                    пустой'
            )
        ]);
        $data_errors_date_sold = [];
        foreach ($validator->validate($input, $constraint) as $key => $value_error) {

            $data_errors_date_receipt_auto_parts_warehouse[$key] = [
                $value_error->getPropertyPath() => $value_error->getMessage()
            ];
        }
        $data_errors_auto_parts_sold = array_merge($data_errors_auto_parts_sold, $data_errors_date_sold);
*/
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
                    меньше {{ min }} и не больше {{ max }} количества автодеталей на складе',
                )
            ]),
            'quantity_sold_auto_parts_warehouse_error' => new Range(
                min: 1,
                max: $quantity_auto_parts_warehouse,
                notInRangeMessage: 'Форма Количество не может быть 
                    меньше {{ min }} и не больше {{ max }} количества автодеталей на складе',
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
        $this->autoPartsSoldCommand->setIdAutoPartsWarehouse($auto_parts_warehouse);
        $this->autoPartsSoldCommand->setQuantitySold($quantity_sold);
        $this->autoPartsSoldCommand->setPriceSold($price_sold);
        $this->autoPartsSoldCommand->setDateSold($date_sold);
        $this->autoPartsSoldCommand->setIdSold($part_number);
        $this->autoPartsSoldCommand->setSoldStatus($date_receipt_auto_parts_warehouse);


        $successfully_save = $this->autoPartsSoldRepositoryInterface->save($this->autoPartsSoldCommand);

        $id = $successfully_save['save'];
        return $id;
    }
}
