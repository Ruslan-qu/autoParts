<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsPaymentMethod\SavePaymentMethodCommand;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\PaymentMethod;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\PaymentMethodRepositoryInterface;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\CommandsPaymentMethod\DTOCommands\DTOPaymentMethodCommand\PaymentMethodCommand;

final class SavePaymentMethodCommandHandler
{

    public function __construct(
        private InputErrorsAutoPartsWarehouse $inputErrorsAutoPartsWarehouse,
        private PaymentMethodRepositoryInterface $paymentMethodRepositoryInterface
    ) {}

    public function handler(PaymentMethodCommand $paymentMethodCommand): int
    {

        $method = mb_ucfirst(mb_strtolower(preg_replace(
            '#\s#',
            '',
            $paymentMethodCommand->getMethod()
        )));

        $id_participant = $paymentMethodCommand->getIdParticipant();
        $this->inputErrorsAutoPartsWarehouse->userIsNotIdentified($id_participant);

        /* Подключаем валидацию и прописываем условида валидации */
        $validator = Validation::createValidator();

        $input = [
            'method_error' => [
                'NotBlank' => $method,
                'Regex' => $method,
            ]
        ];

        $constraint = new Collection([
            'method_error' => new Collection([
                'NotBlank' => new NotBlank(
                    message: 'Форма Поставщик не может быть пустой'
                ),
                'Regex' => new Regex(
                    pattern: '/^[а-яё]*$/ui',
                    message: 'Форма Поставщик содержит недопустимые символы'
                )
            ])
        ]);

        $errors_validate = $validator->validate($input, $constraint);
        $this->inputErrorsAutoPartsWarehouse->errorValidate($errors_validate);

        /* Валидация дублей */
        $count_duplicate = $this->paymentMethodRepositoryInterface
            ->numberDoubles(['method' => $method]);
        $this->inputErrorsAutoPartsWarehouse->errorDuplicate($count_duplicate);

        $payment_method = new PaymentMethod;
        $payment_method->setMethod($method);
        $payment_method->setIdParticipant($id_participant);

        return $this->paymentMethodRepositoryInterface->save($payment_method);
    }
}
