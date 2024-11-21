<?php

namespace App\Sales\InfrastructureSales\ApiSales\FormSales;

use App\Form\Type\EntityHiddenType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\AutoPartsWarehouse;

class AutoPartsSoldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity_sold', IntegerType::class, [
                'label' => 'Кол-во',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Форма не может быть 
                    пустой'
                    ]),
                    new Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'Форма содержит 
                    недопустимые символы'
                    ]),
                ],
            ])
            ->add('price_sold', NumberType::class, [
                'label' => 'Цена',
                'invalid_message' => 'Форма содержит 
                    недопустимые символы',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Форма не может быть 
                    пустой'
                    ]),
                    new Regex([
                        'pattern' => '/^[\d]+[\.,]?[\d]*$/',
                        'message' => 'Форма содержит 
                    недопустимые символы'
                    ]),
                ],
            ])
            ->add('date_sold', DateType::class, [
                'label' => 'Дата продажи',
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Форма не может быть 
                    пустой'
                    ])
                ],
            ])
            ->add('id_auto_parts_warehouse', EntityHiddenType::class, [
                'class' => AutoPartsWarehouse::class,
                'choice_label' => 'id'
            ])
            ->add('button_add_cart', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
