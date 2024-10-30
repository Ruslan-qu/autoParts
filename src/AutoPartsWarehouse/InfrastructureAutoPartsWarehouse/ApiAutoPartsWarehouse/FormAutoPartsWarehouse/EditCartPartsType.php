<?php

namespace App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\FormAutoPartsWarehouse;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class EditCartPartsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity_sold', IntegerType::class, [
                'label' => 'Кол-во',
                'required' => false,
                /* 'constraints' => [
                new NotBlank([
                    'message' => 'Форма не может быть 
                пустой'
                ]),
                new Regex([
                    'pattern' => '/^\d+$/',
                    'message' => 'Форма содержит 
                недопустимые символы'
                ]),
            ],*/
            ])
            ->add('price_sold', NumberType::class, [
                'label' => 'Цена',
                'invalid_message' => 'Форма содержит 
                недопустимые символы',
                'required' => false,
                /*'constraints' => [
                new NotBlank([
                    'message' => 'Форма не может быть 
                пустой'
                ]),
                new Regex([
                    'pattern' => '/^[\d]+[\.,]?[\d]*$/',
                    'message' => 'Форма содержит 
                недопустимые символы'
                ]),
            ],*/
            ])
            ->add('date_sold', DateType::class, [
                'label' => 'Дата продажи',
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'required' => false,
                /*'constraints' => [
                new NotBlank([
                    'message' => 'Форма не может быть 
                пустой'
                ])
            ],*/
            ])
            ->add('id', HiddenType::class)
            ->add('button_edit_cart_auto_parts', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
