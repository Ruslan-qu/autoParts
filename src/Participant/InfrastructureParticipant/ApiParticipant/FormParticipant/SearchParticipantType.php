<?php

namespace App\Participant\InfrastructureParticipant\ApiParticipant\FormParticipant;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;

class SearchParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('participant', EntityType::class, [
                'label' => 'Email пользователя',
                'class' => Participant::class,
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    $a = $er->createQueryBuilder('p')
                        ->orderBy('p.id', 'ASC');
                    //dd($a->getParameters());
                    return $a;
                },
                'choice_label' => 'email'
            ])
            ->add('button_participant', SubmitType::class, [
                'label' => 'Поиск'
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
