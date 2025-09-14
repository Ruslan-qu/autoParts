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
use App\Counterparty\DomainCounterparty\DomainModelCounterparty\EntityCounterparty\Counterparty;

class SaveAutoPartsEmailType extends AbstractType
{

    public function __construct(
        private Security $security
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            /* ->add('id_counterparty', EntityType::class, [
                'label' => 'Поставщик',
                'class' => Counterparty::class,
                'query_builder' => function (EntityRepository $er): QueryBuilder {

                    return $er->createQueryBuilder('p')
                        ->where('p.id_participant = :id_participant')
                        ->setParameter('id_participant', $this->security->getUser())
                        ->orderBy('p.name_counterparty', 'ASC');
                },
                'choice_label' => 'name_counterparty',
                'required' => false
            ])*/
            ->add('button_save_email', SubmitType::class, [
                'label' => 'Принять'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
