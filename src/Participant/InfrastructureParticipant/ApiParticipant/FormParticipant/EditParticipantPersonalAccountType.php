<?php

namespace App\Participant\InfrastructureParticipant\ApiParticipant\FormParticipant;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class EditParticipantPersonalAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [
                    new Email([
                        'message' => 'Форма содержит 
                        недопустимые символы'
                    ]),
                    new NotBlank([
                        'message' => 'Форма не может быть 
                        пустой'
                    ])

                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Пароль',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Форма не может быть 
                        пустой'
                    ])

                ]
            ])
            ->add('id', HiddenType::class)
            ->add('button_participant', SubmitType::class, [
                'label' => 'Изменить'
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
