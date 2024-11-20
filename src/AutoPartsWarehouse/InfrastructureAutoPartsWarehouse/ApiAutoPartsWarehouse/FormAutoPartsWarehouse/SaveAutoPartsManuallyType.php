<?php

namespace App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\FormAutoPartsWarehouse;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\PaymentMethod;

class SaveAutoPartsManuallyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id_details', TextType::class, [
                'label' => '№ Детали',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Форма не может быть 
                        пустой'
                    ]),
                    new Regex([
                        'pattern' => '/^[\da-z]*$/i',
                        //'match' => false,
                        'message' => 'Форма содержит 
                        недопустимые символы'
                    ]),
                ]
            ])
            ->add('id_counterparty', EntityType::class, [
                'label' => 'Поставщик',
                'class' => Counterparty::class,
                'choice_label' => 'name_counterparty',
                'required' => false,
            ])
            ->add('quantity', IntegerType::class, [
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
                ]
            ])
            ->add('price', NumberType::class, [
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
                ]
            ])
            ->add('date_receipt_auto_parts_warehouse', DateType::class, [
                'label' => 'Дата прихода',
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Форма не может быть 
                        пустой'
                    ])
                ]
            ])
            ->add('id_payment_method', EntityType::class, [
                'label' => 'Сп. оплаты',
                'class' => PaymentMethod::class,
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.method', 'DESC');
                },
                'choice_label' => 'method'
            ])
            ->add('id', HiddenType::class)
            ->add('button_save_manually', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
