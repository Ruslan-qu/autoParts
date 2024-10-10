<?php

namespace App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\FormAutoPartsWarehouse;

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
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Axles;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Sides;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Bodies;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartName;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\CarBrands;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Availability;
use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;
use App\AutoPartsWarehouse\DomainAutoPartsWarehouse\DomainModelAutoPartsWarehouse\EntityAutoPartsWarehouse\PaymentMethod;

class SearchAutoPartsWarehouseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id_part_name', EntityType::class, [
                'label' => 'Название детали',
                'class' => PartName::class,
                'choice_label' => 'part_name',
                'required' => false
            ])
            ->add('id_car_brand', EntityType::class, [
                'label' => 'Марка',
                'class' => CarBrands::class,
                'choice_label' => 'car_brand',
                'required' => false
            ])
            ->add('id_side', EntityType::class, [
                'label' => 'Сторона',
                'class' => Sides::class,
                'choice_label' => 'side',
                'required' => false
            ])
            ->add('id_body', EntityType::class, [
                'label' => 'Кузов',
                'class' => Bodies::class,
                'choice_label' => 'body',
                'required' => false
            ])
            ->add('id_axle', EntityType::class, [
                'label' => 'Перед Зад',
                'class' => Axles::class,
                'choice_label' => 'axle',
                'required' => false
            ])
            ->add('id', HiddenType::class)
            ->add('button_search_auto_parts_warehouse', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}