<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsPaymentMethod\EditPaymentMethodCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\PaymentMethodRepositoryInterface;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsPaymentMethod\DTOCommands\DTOPaymentMethodCommand\PaymentMethodCommand;

final class EditPaymentMethodCommandHandler
{

    public function __construct(
        private InputErrorsAutoPartsWarehouse $inputErrorsAutoPartsWarehouse,
        private PaymentMethodRepositoryInterface $paymentMethodRepositoryInterface
    ) {}

    public function handler(PaymentMethodCommand $paymentMethodCommand): ?int
    {
        $edit_method = mb_ucfirst(mb_strtolower($paymentMethodCommand->getMethod()));

        $participant = $paymentMethodCommand->getIdParticipant();
        $this->inputErrorsAutoPartsWarehouse->userIsNotIdentified($participant);

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $input = [
            'method_error' => [
                'NotBlank' => $edit_method,
                'Regex' => $edit_method,
            ]
        ];

        $constraint = new Collection([
            'method_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма Способ оплаты не может быть пустой'
                ),
                'Regex' => new Regex(
                    pattern: '/^[а-яё\s]*$/ui',
                    message: 'Форма Способ оплаты содержит недопустимые символы'
                )
            ])
        ]);

        $errors_validate = $validator->validate($input, $constraint);
        $this->inputErrorsAutoPartsWarehouse->errorValidate($errors_validate);

        $id = $paymentMethodCommand->getId();
        $this->inputErrorsAutoPartsWarehouse->emptyData($id);

        $payment_method = $this->paymentMethodRepositoryInterface->findPaymentMethod($id);
        $this->inputErrorsAutoPartsWarehouse->emptyEntity($payment_method);

        $this->countDuplicate($edit_method, $payment_method->getMethod(), $participant);

        $payment_method->setMethod($edit_method);

        return $this->paymentMethodRepositoryInterface->edit($payment_method);
    }

    private function countDuplicate(string $edit_method, string $method, Participant $participant): static
    {
        if ($edit_method != $method) {
            /* Валидация дублей */
            $count_duplicate = $this->paymentMethodRepositoryInterface
                ->numberDoubles([
                    'method' => $edit_method,
                    'id_participant' => $participant
                ]);
            $this->inputErrorsAutoPartsWarehouse->errorDuplicate($count_duplicate);
        }

        return $this;
    }
}
