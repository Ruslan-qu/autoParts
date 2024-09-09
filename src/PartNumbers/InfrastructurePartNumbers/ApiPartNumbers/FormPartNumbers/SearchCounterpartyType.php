<?php

namespace App\Counterparty\InfrastructureCounterparty\ApiCounterparty\FormCounterparty;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchCounterpartyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name_counterparty', TextType::class, [
                'label' => 'Поставщик',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[\da-z]*$/i',
                        //'match' => false,
                        'message' => 'Форма содержит недопустимые символы'
                    ])
                ]
            ])
            /*->add('form_search_mail_counterparty', EmailType::class, [
            'label' => 'E-mail',
            'constraints' => [
                new Email([
                    'message' => 'Форма содержит недопустимые символы'
                ])
            ]
        ])
        ->add('form_search_manager_phone', TelType::class, [
            'label' => 'Телефон менеджера',
            'required' => false,
            'constraints' => [
                new Regex([
                    'pattern' => '/\+{1}\d{11}/',
                    //'match' => false,
                    'message' => 'Форма содержит<br>1) Недопустимые символы<br>2) Нет знака +<br>3) Неверное количество цифр'
                ]),
            ]
        ])
        ->add('form_search_delivery_phone', TelType::class, [
            'label' => 'Телефон доставки',
            'required' => false,
            'constraints' => [
                new Regex([
                    'pattern' => '/\+{1}\d{11}/',
                    //'match' => false,
                    'message' => 'Форма содержит<br>1) Недопустимые символы<br>2) Нет знака +<br>3) Неверное количество цифр'
                ])
            ]
        ])*/
            ->add('button_search_counterparty', SubmitType::class);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
