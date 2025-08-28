<?php

namespace App\PartNumbers\ApplicationPartNumbers\CommandsBodies\EditBodiesCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\PartNumbers\ApplicationPartNumbers\ErrorsPartNumbers\InputErrorsPartNumbers;
use App\PartNumbers\DomainPartNumbers\RepositoryInterfacePartNumbers\BodiesRepositoryInterface;
use App\PartNumbers\ApplicationPartNumbers\CommandsBodies\DTOCommands\DTOBodiesCommand\BodiesCommand;

final class EditBodiesCommandHandler
{
    public function __construct(
        private InputErrorsPartNumbers $inputErrorsPartNumbers,
        private BodiesRepositoryInterface $bodiesRepositoryInterface
    ) {}

    public function handler(BodiesCommand $bodiesCommand): ?int
    {

        $edit_body = mb_ucfirst(mb_strtolower(preg_replace(
            '#\s#',
            '',
            $bodiesCommand->getBody()
        )));

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();
        $input = [
            'body_error' => [
                'NotBlank' => $edit_body,
                'Regex' => $edit_body,
            ]
        ];

        $constraint = new Collection([
            'body_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма Кузов не может быть пустой'
                ),
                'Regex' => new Regex(
                    pattern: '/^[а-яё]*$/ui',
                    message: 'Форма Кузов содержит недопустимые символы'
                )
            ])
        ]);

        $errors_validate = $validator->validate($input, $constraint);
        $this->inputErrorsPartNumbers->errorValidate($errors_validate);


        $id = $bodiesCommand->getId();
        $this->inputErrorsPartNumbers->emptyData($id);

        $bodies = $this->bodiesRepositoryInterface->findBodies($id);
        $this->inputErrorsPartNumbers->emptyEntity($bodies);

        $this->countDuplicate($edit_body, $bodies->getBody());

        $bodies->setBody($edit_body);

        $successfully_edit = $this->bodiesRepositoryInterface->edit($bodies);

        $id = $successfully_edit['edit'];

        return $id;
    }

    private function countDuplicate(string $edit_body, string $body): static
    {
        if ($edit_body != $body) {
            /* Валидация дублей */
            $count_duplicate = $this->bodiesRepositoryInterface
                ->numberDoubles(['body' => $edit_body]);
            $this->inputErrorsPartNumbers->errorDuplicate($count_duplicate);
        }

        return $this;
    }
}
