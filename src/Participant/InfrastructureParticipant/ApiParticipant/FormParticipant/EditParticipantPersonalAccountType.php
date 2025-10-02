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
use Symfony\Component\Validator\Constraints\PasswordStrength;

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
            ->add('old_password', PasswordType::class, [
                'label' => 'Старый Пароль',
                'toggle' => true,
                'required' => false,
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Новый Пароль',
                'required' => false,
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new PasswordStrength([
                        'message' => 'Ваш пароль слишком легко угадать. 
                        Введите более надежный пароль.',
                    ]),
                ],
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
