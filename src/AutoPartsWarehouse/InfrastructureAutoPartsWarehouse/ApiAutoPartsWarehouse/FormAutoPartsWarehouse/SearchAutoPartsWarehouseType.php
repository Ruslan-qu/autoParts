<?php

namespace App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\FormAutoPartsWarehouse;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Axles;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Sides;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\Bodies;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\PartName;
use App\PartNumbers\DomainPartNumbers\DomainModelPartNumbers\EntityPartNumbers\CarBrands;


class SearchAutoPartsWarehouseType extends AbstractType
{
    public function __construct(
        private Security $security
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id_part_name', EntityType::class, [
                'label' => 'Название детали',
                'class' => PartName::class,
                'query_builder' => function (EntityRepository $er): QueryBuilder {

                    return $er->createQueryBuilder('p')
                        ->where('p.id_participant = :id_participant')
                        ->setParameter('id_participant', $this->security->getUser())
                        ->orderBy('p.part_name', 'ASC');
                },
                'choice_label' => 'part_name',
                'required' => false
            ])
            ->add('id_car_brand', EntityType::class, [
                'label' => 'Марка',
                'class' => CarBrands::class,
                'query_builder' => function (EntityRepository $er): QueryBuilder {

                    return $er->createQueryBuilder('c')
                        ->where('c.id_participant = :id_participant')
                        ->setParameter('id_participant', $this->security->getUser())
                        ->orderBy('c.car_brand', 'ASC');
                },
                'choice_label' => 'car_brand',
                'required' => false
            ])
            ->add('id_side', EntityType::class, [
                'label' => 'Сторона',
                'class' => Sides::class,
                'query_builder' => function (EntityRepository $er): QueryBuilder {

                    return $er->createQueryBuilder('s')
                        ->where('s.id_participant = :id_participant')
                        ->setParameter('id_participant', $this->security->getUser())
                        ->orderBy('s.side', 'ASC');
                },
                'choice_label' => 'side',
                'required' => false
            ])
            ->add('id_body', EntityType::class, [
                'label' => 'Кузов',
                'class' => Bodies::class,
                'query_builder' => function (EntityRepository $er): QueryBuilder {

                    return $er->createQueryBuilder('b')
                        ->where('b.id_participant = :id_participant')
                        ->setParameter('id_participant', $this->security->getUser())
                        ->orderBy('b.body', 'ASC');
                },
                'choice_label' => 'body',
                'required' => false
            ])
            ->add('id_axle', EntityType::class, [
                'label' => 'Перед Зад',
                'class' => Axles::class,
                'query_builder' => function (EntityRepository $er): QueryBuilder {

                    return $er->createQueryBuilder('ax')
                        ->where('ax.id_participant = :id_participant')
                        ->setParameter('id_participant', $this->security->getUser())
                        ->orderBy('ax.axle', 'ASC');
                },
                'choice_label' => 'axle',
                'required' => false
            ])
            ->add('is_customer_order', CheckboxType::class, [
                'label' => 'Заказ',
                'required' => false,
            ])
            ->add('button_search_auto_parts_warehouse', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
