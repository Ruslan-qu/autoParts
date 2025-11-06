<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryCarBrands\SearchCarBrandsQuery;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\CarBrandsRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryCarBrands\DTOQuery\DTOCarBrandsQuery\CarBrandsQuery;


final class SearchCarBrandsQueryHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private CarBrandsRepositoryInterface $carBrandsRepositoryInterface
    ) {}

    public function handler(CarBrandsQuery $carBrandsQuery): ?array
    {

        $car_brand = ucfirst(strtolower($carBrandsQuery->getCarBrand()));

        $id_participant = $carBrandsQuery->getIdParticipant();

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();
        $input = [
            'car_brand_error' => [
                'NotBlank' => $car_brand,
                'Regex' => $car_brand,
            ]
        ];

        $constraint = new Collection([
            'car_brand_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма Марка не может быть пустой'
                ),
                'Regex' => new Regex(
                    pattern: '/^[a-z\s\d]*$/i',
                    message: 'Форма Марка содержит недопустимые символы'
                )
            ])
        ]);

        $errors_validate = $validator->validate($input, $constraint);
        $this->inputErrorsPartNumbers->errorValidate($errors_validate);

        $find_one_by_car_brands['car_brands'] = $this->carBrandsRepositoryInterface->findOneByCarBrands($car_brand, $id_participant);
        $this->inputErrorsPartNumbers->issetEntity($find_one_by_car_brands['car_brands']);

        return $find_one_by_car_brands;
    }
}
