<?php

namespace App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormPartNumbers;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Axles;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Sides;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Bodies;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartName;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\CarBrands;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Availability;

class SavePartNumbersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('part_number', TextType::class, [
                'label' => 'Номер детали',
                'constraints' => [
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
                ]
            ])
            ->add('id_original_number', TextType::class, [
                'label' => 'Номер оригинал',
                'required' => false,
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[\da-z]*$/i',
                        //'match' => false,
                        'message' => 'Форма содержит 
                        недопустимые символы'
                    ])
                ]
            ])
            ->add('manufacturer', TextType::class, [
                'label' => 'Производитель',
                'required' => false,
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[\da-z]*$/i',
                        //'match' => false,
                        'message' => 'Форма содержит 
                        недопустимые символы'
                    ])
                ]
            ])
            ->add('additional_descriptions', TextareaType::class, [
                'label' => 'Описание детали',
                'required' => false,
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[а-яё\w\s]*$/ui',
                        //'match' => false,
                        'message' => 'Форма содержит 
                        недопустимые символы'
                    ]),
                ]
            ])
            ->add('id_part_name', EntityType::class, [
                'label' => 'Название детали',
                'class' => PartName::class,
                'choice_label' => 'part_name',
                'required' => false
            ])
            ->add('id_car_brand', EntityType::class, [
                'label' => 'Марка',
                'class' => CarBrands::class,
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.id', 'ASC');
                },
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

            ->add('id_in_stock', EntityType::class, [
                'label' => 'Наличие',
                'class' => Availability::class,
                'choice_label' => 'in_stock',
                'required' => false
            ])
            ->add('button_part_number', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
