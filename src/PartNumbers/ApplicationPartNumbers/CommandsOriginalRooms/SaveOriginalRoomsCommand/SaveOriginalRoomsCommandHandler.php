<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsOriginalRooms\SaveOriginalRoomsCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Availability;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\AvailabilityRepositoryInterface;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\OriginalRoomsRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsAvailability\DTOCommands\DTOAvailabilityCommand\AvailabilityCommand;
use App\PartNumbers\ApplicationPartNumbers\CommandsOriginalRooms\DTOCommands\DTOOriginalRoomsCommand\OriginalRoomsCommand;

final class SaveOriginalRoomsCommandHandler
{

    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private OriginalRoomsRepositoryInterface $originalRoomsRepositoryInterface
    ) {}

    public function handler(OriginalRoomsCommand $originalRoomsCommand): ?int
    {
        dd($originalRoomsCommand);

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $original_number = strtolower(preg_replace(
            '#\s#u',
            '',
            $originalRoomsCommand->getOriginalNumber()
        ));

        $original_manufacturer = strtolower(preg_replace(
            '#\s#u',
            '',
            $originalRoomsCommand->getOriginalManufacturer()
        ));

        $replacing_original_number = $originalRoomsCommand->getReplacingOriginalNumber();

        $id_participant = $originalRoomsCommand->getIdParticipant();

        $input = [
            'in_stock_error' => [
                'NotBlank' => $in_stock,
                'Regex' => $in_stock,
            ]
        ];

        $constraint = new Collection([
            'in_stock_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма Наличие не может быть пустой'
                ),
                'Regex' => new Regex(
                    pattern: '/^[а-яё\s]*$/ui',
                    message: 'Форма Наличие содержит недопустимые символы'
                )
            ])
        ]);

        $errors_validate = $validator->validate($input, $constraint);
        $this->inputErrorsPartNumbers->errorValidate($errors_validate);

        /* Валидация дублей */
        $count_duplicate = $this->availabilityRepositoryInterface
            ->numberDoubles(['in_stock' => $in_stock]);
        $this->inputErrorsPartNumbers->errorDuplicate($count_duplicate);

        $availability = new Availability;
        $availability->setInStock($in_stock);
        $availability->setIdParticipant($id_participant);

        return $this->availabilityRepositoryInterface->save($availability);
    }

    private function isArrayReplacingOriginalNumber($replacing_original_number): ?string
    {
        if (empty($data)) {

            $arr_data_errors = ['Error' => 'Пустые данные'];
            $json_arr_data_errors = json_encode($arr_data_errors, JSON_UNESCAPED_UNICODE);
            throw new UnprocessableEntityHttpException($json_arr_data_errors);
        }

        return $this;
    }
}
