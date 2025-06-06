<?php

namespace App\Participant\InfrastructureParticipant\ApiParticipant\FormParticipant;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\PasswordStrength;
use App\Participant\DomainParticipant\DomainModelParticipant\Participant;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Форма не может быть 
                        пустой'
                    ]),
                    new Email([
                        'message' => 'Форма содержит 
                    недопустимые символы'
                    ])
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Пароль',
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Форма не может быть 
                        пустой',
                    ]),
                    new PasswordStrength([
                        'message' => 'Ваш пароль слишком легко угадать. 
                        Введите более надежный пароль.',
                    ]),
                ],
            ])
            ->add('button_registration_participant', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
