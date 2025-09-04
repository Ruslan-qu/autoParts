<?php

namespace App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryPaymentMethod\SearchPaymentMethodQuery;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\QueryPaymentMethod\DTOQuery\PaymentMethodQuery;
use App\AutoPartsWarehouse\ApplicationAutoPartsWarehouse\ErrorsAutoPartsWarehouse\InputErrorsAutoPartsWarehouse;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\PaymentMethod;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\RepositoryInterfaceAutoPartsWarehouse\PaymentMethodRepositoryInterface;


final class SearchPaymentMethodQueryHandler
{

    public function __construct(
        private InputErrorsAutoPartsWarehouse $inputErrorsAutoPartsWarehouse,
        private PaymentMethodRepositoryInterface $paymentMethodRepositoryInterface
    ) {}

    public function handler(PaymentMethodQuery $paymentMethodQuery): ?PaymentMethod
    {

        $method = mb_ucfirst(mb_strtolower($paymentMethodQuery->getMethod()));
        $this->inputErrorsAutoPartsWarehouse->emptyData($method);

        $id_participant = $paymentMethodQuery->getIdParticipant();
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

        $arr_еntity = $this->paymentMethodRepositoryInterface->findOneByPaymentMethod($method, $id_participant);
        $this->inputErrorsAutoPartsWarehouse->emptyEntity($arr_еntity);

        return $arr_еntity;
    }
}
