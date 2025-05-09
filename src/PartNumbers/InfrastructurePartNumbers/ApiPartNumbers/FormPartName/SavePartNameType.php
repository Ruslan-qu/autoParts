<?php

namespace App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormPartName;

use App\Form\Type\EntityHiddenType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;

class SavePartNameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('part_name', TextType::class, [
                'label' => 'Название детали',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[а-яё\s]*$/ui',
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
            ->add('id_participant', EntityHiddenType::class, [
                'class' => Participant::class,
                'choice_label' => 'id'
            ])
            ->add('button_part_name', SubmitType::class, [
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
