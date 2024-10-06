<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\SaveAutoPartsWarehouseCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\RepositoryAutoPartsWarehouse\AutoPartsWarehouseRepository;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsAutoPartsWarehouse\DTOCommands\DTOAutoPartsWarehouseCommand\CreateAutoPartsWarehouseCommand;

final class CreateSaveAutoPartsWarehouseCommandHandler
{

    public function __construct(
        private AutoPartsWarehouseRepository $autoPartsWarehouseRepository,
        private AutoPartsWarehouse $autoPartsWarehouse
    ) {}

    public function handler(CreateAutoPartsWarehouseCommand $createAutoPartsWarehouseCommand): array
    {
        dd($createAutoPartsWarehouseCommand);
        $quantity = $createAutoPartsWarehouseCommand->getQuantity();

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

        $price = $createAutoPartsWarehouseCommand->getPrice();

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
                'Type' => new Type('string'),
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

        $data_errors_part_number = array_merge($data_errors_part_number, $data_errors_price);


        $manufacturer = strtolower(preg_replace(
            '#\s#',
            '',
            $createPartNumbersCommand->getManufacturer()
        ));

        if (!empty($manufacturer)) {
            $input = [
                'manufacturer_error' => [
                    'Type' => $manufacturer,
                    'Regex' => $manufacturer
                ]
            ];

            $constraint = new Collection([
                'manufacturer_error' => new Collection([
                    'Type' => new Type('string'),
                    'Regex' => new Regex(
                        pattern: '/^[\da-z]*$/i',
                        message: 'Форма Производитель содержит недопустимые символы'
                    )
                ])
            ]);
            $data_errors_manufacturer = [];
            foreach ($validator->validate($input, $constraint) as $key => $value_error) {

                $data_errors_manufacturer[$key] = [
                    $value_error->getPropertyPath() => $value_error->getMessage()
                ];
            }

            $data_errors_part_number = array_merge($data_errors_part_number, $data_errors_manufacturer);
        }


        $id_part_name = $createPartNumbersCommand->getIdPartName();

        $id_car_brand = $createPartNumbersCommand->getIdCarBrand();

        $id_side = $createPartNumbersCommand->getIdSide();

        $id_body = $createPartNumbersCommand->getIdBody();

        $id_axle = $createPartNumbersCommand->getIdAxle();

        $id_in_stock = $createPartNumbersCommand->getIdInStock();

        $id_original_number = $createPartNumbersCommand->getIdOriginalNumber();

        if (!empty($data_errors_part_number)) {

            $json_arr_data_errors = json_encode($data_errors_part_number, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }
        /* Валидация дублей */
        $number_doubles = $this->part_numbers_repository_interface
            ->numberDoubles(['part_number' => $part_number]);

        if ($number_doubles != 0) {

            $arr_errors_number_doubles['errors'] = [
                'doubles' => 'Номер детали существует'
            ];

            return $arr_errors_number_doubles;
        }

        $this->entity_part_numbers_from_manufacturers->setPartNumber($part_number);
        $this->entity_part_numbers_from_manufacturers->setManufacturer($manufacturer);
        $this->entity_part_numbers_from_manufacturers->setAdditionalDescriptions($additional_descriptions);
        $this->entity_part_numbers_from_manufacturers->setIdPartName($id_part_name);
        $this->entity_part_numbers_from_manufacturers->setIdCarBrand($id_car_brand);
        $this->entity_part_numbers_from_manufacturers->setIdSide($id_side);
        $this->entity_part_numbers_from_manufacturers->setIdBody($id_body);
        $this->entity_part_numbers_from_manufacturers->setIdAxle($id_axle);
        $this->entity_part_numbers_from_manufacturers->setIdInStock($id_in_stock);
        $this->entity_part_numbers_from_manufacturers->setIdOriginalNumber($id_original_number);


        $successfully_save = $this->part_numbers_repository_interface->save($this->entity_part_numbers_from_manufacturers);

        $successfully['successfully'] = $successfully_save;
        return $successfully;
    }
}
