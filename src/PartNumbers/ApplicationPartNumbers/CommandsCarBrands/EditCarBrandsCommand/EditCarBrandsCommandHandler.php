<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsCarBrands\EditCarBrandsCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\CarBrandsRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsCarBrands\DTOCommands\DTOCarBrandsCommand\CarBrandsCommand;

final class EditCarBrandsCommandHandler
{
    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private CarBrandsRepositoryInterface $carBrandsRepositoryInterface
    ) {}

    public function handler(CarBrandsCommand $carBrandsCommand): ?int
    {
        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $edit_car_brand = $carBrandsCommand->getCarBrand();

        $input = [
            'car_brand_error' => [
                'NotBlank' => $edit_car_brand,
                'Regex' => $edit_car_brand,
            ]
        ];

        $constraint = new Collection([
            'car_brand_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма Марка не может быть пустой'
                ),
                'Regex' => new Regex(
                    pattern: '/^[a-z\s]*$/i',
                    message: 'Форма Марка содержит недопустимые символы'
                )
            ])
        ]);

        $errors_validate = $validator->validate($input, $constraint);
        $this->inputErrorsPartNumbers->errorValidate($errors_validate);


        $id = $carBrandsCommand->getId();
        $this->inputErrorsPartNumbers->emptyData($id);

        $car_brands = $this->carBrandsRepositoryInterface->findCarBrands($id);
        $this->inputErrorsPartNumbers->emptyEntity($car_brands);

        $this->countDuplicate($edit_car_brand, $car_brands->getCarBrand());

        $car_brands->setCarBrand($edit_car_brand);


        $successfully_edit = $this->carBrandsRepositoryInterface->edit($car_brands);

        $id = $successfully_edit['edit'];

        return $id;
    }

    private function countDuplicate(string $edit_car_brand, string $car_brand): static
    {
        if ($edit_car_brand != $car_brand) {
            /* Валидация дублей */
            $count_duplicate = $this->carBrandsRepositoryInterface
                ->numberDoubles(['car_brand' => $edit_car_brand]);
            $this->inputErrorsPartNumbers->errorDuplicate($count_duplicate);
        }

        return $this;
    }
}
