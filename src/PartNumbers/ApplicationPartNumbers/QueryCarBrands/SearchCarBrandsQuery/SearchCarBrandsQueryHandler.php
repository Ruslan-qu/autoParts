<?php

namespace App\PartNumbers\ApplicationPartNumbers\QueryCarBrands\SearchCarBrandsQuery;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\CarBrandsRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\QueryCarBrands\DTOQuery\DTOPartNameQuery\CarBrandsQuery;


final class SearchCarBrandsQueryHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private CarBrandsRepositoryInterface $carBrandsRepositoryInterface
    ) {}

    public function handler(CarBrandsQuery $carBrandsQuery): ?array
    {

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $car_brand = $carBrandsQuery->getCarBrand();
        $id_participant = $carBrandsQuery->getIdParticipant();

        $input = [
            'part_name_error' => [
                'NotBlank' => $car_brand,
                'Regex' => $car_brand,
            ]
        ];

        $constraint = new Collection([
            'part_name_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма Марка не может быть пустой'
                ),
                'Regex' => new Regex(
                    pattern: '/^[а-яё\s]*$/ui',
                    message: 'Форма Марка содержит недопустимые символы'
                )
            ])
        ]);

        $errors_validate = $validator->validate($input, $constraint);
        $this->inputErrorsPartNumbers->errorValidate($errors_validate);

        $find_one_by_car_brands['car_brands'] = $this->carBrandsRepositoryInterface->findOneByCarBrands($car_brand, $id_participant);

        return $find_one_by_car_brands;
    }
}
