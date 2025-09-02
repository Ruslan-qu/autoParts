<?php

namespace App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\FormPaymentMethod;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SavePaymentMethodType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('$method', TextType::class, [
                'label' => 'Способ оплаты',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[а-яё]*$/ui',
                        //'match' => false,
                        'message' => 'Форма содержит 
                    недопустимые символы'
                    ]),
                    new NotBlank([
                        'message' => 'Форма не может быть 
                    пустой'
                    ])
                ]
            ])
            ->add('button_$method', SubmitType::class, [
                'label' => 'Сохранить'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
