<?php

namespace App\Counterparty\InfrastructureCounterparty\ApiCounterparty\FormCounterparty;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EditCounterpartyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name_counterparty', TextType::class, [
                'label' => 'Поставщик',
                'required' => false,
                /* 'constraints' => [
                    new Regex([
                        'pattern' => '/^[\da-z]*$/i',
                        //'match' => false,
                        'message' => 'Форма содержит 
                        недопустимые символы'
                    ]),
                    new NotBlank([
                        'message' => 'Форма не может быть 
                        пустой'
                    ])

                ]*/
            ])
            ->add('mail_counterparty', EmailType::class, [
                'label' => 'E-mail',
                'required' => false,
                /*'constraints' => [
                    new Email([
                        'message' => 'Форма содержит 
                        недопустимые символы'
                    ]),
                ]*/
            ])
            ->add('manager_phone', TelType::class, [
                'label' => 'Тел. менеджера',
                'required' => false,
                /*'constraints' => [
                    new Regex([
                        'pattern' => '/^\+{1}\d{11}$/',
                        //'match' => false,
                        'message' => 'Форма содержит
                        Недопустимые символы
                        или Нет знака +
                        или Неверное количество цифр'
                    ]),
                ]*/
            ])
            ->add('delivery_phone', TelType::class, [
                'label' => 'Тел. доставки',
                'required' => false,
                /*'constraints' => [
                    new Regex([
                        'pattern' => '/^\+{1}\d{11}$/',
                        //'match' => false,
                        'message' => 'Форма содержит
                        Недопустимые символы
                        или Нет знака +
                        или Неверное количество цифр'
                    ])
                ]*/
            ])
            ->add('id', HiddenType::class)
            ->add('button_counterparty', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
