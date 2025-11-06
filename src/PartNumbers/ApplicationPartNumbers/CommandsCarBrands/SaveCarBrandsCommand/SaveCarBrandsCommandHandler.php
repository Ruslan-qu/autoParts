<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsCarBrands\SaveCarBrandsCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\CarBrands;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\CarBrandsRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsCarBrands\DTOCommands\DTOCarBrandsCommand\CarBrandsCommand;

final class SaveCarBrandsCommandHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private CarBrandsRepositoryInterface $carBrandsRepositoryInterface
    ) {}

    public function handler(CarBrandsCommand $carBrandsCommand): ?int
    {

        $car_brand = ucfirst(strtolower($carBrandsCommand->getCarBrand()));

        $id_participant = $carBrandsCommand->getIdParticipant();

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

        /* Валидация дублей */
        $count_duplicate = $this->carBrandsRepositoryInterface
            ->numberDoubles([
                'car_brand' => $car_brand,
                'id_participant' => $id_participant
            ]);
        $this->inputErrorsPartNumbers->errorDuplicate($count_duplicate);

        $carBrands = new CarBrands;
        $carBrands->setCarBrand($car_brand);
        $carBrands->setIdParticipant($id_participant);

        return $this->carBrandsRepositoryInterface->save($carBrands);
    }
}
