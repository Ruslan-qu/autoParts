<?php

namespace App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\FormAutoPartsWarehouse;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Bundle\SecurityBundle\Security;
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
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\PaymentMethod;

class SaveAutoPartsManuallyType extends AbstractType
{
    public function __construct(
        private Security $security
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('part_number', TextType::class, [
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
                'query_builder' => function (EntityRepository $er): QueryBuilder {

                    return $er->createQueryBuilder('co')
                        ->where('co.id_participant = :id_participant')
                        ->setParameter('id_participant', $this->security->getUser())
                        ->orderBy('co.name_counterparty', 'ASC');
                },
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

                    return $er->createQueryBuilder('p')
                        ->where('p.id_participant = :id_participant')
                        ->setParameter('id_participant', $this->security->getUser())
                        ->orderBy('p.method', 'ASC');
                },
                'choice_label' => 'method'
            ])
            ->add('is_customer_order', CheckboxType::class, [
                'label' => 'Заказ',
                'required' => false,
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
