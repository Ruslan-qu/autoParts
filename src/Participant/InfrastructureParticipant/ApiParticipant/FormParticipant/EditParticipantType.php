<?php

namespace App\Participant\InfrastructureParticipant\ApiParticipant\FormParticipant;

use Symfony\Component\Form\AbstractType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EditParticipantType extends AbstractType
{
    public function __construct(
        private Security $security
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email пользователя',
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
            ->add('isVerified', ChoiceType::class, [
                'label' => 'Блокировка',
                'choices'  => [
                    'Заблокировать' => false,
                    'Разблокировать' => true
                ]
            ])
            ->add('id', HiddenType::class)
            ->add('button_participant', SubmitType::class, [
                'label' => 'Изменить'
            ]);
        if ($this->security->getUser()->getRoles()[0] === 'ROLE_BOSS') {
            $builder->add('roles', ChoiceType::class, [
                'label' => 'Роль',
                'choices'  => [
                    'USER' => 'ROLE_USER',
                    'ADMIN' => 'ROLE_ADMIN',
                    'BOSS' => 'ROLE_BOSS'
                ]
            ]);
        } elseif ($this->security->getUser()->getRoles()[0] === 'ROLE_ADMIN') {
            $builder->add('roles', ChoiceType::class, [
                'label' => 'Роль',
                'choices'  => [
                    'ADMIN' => 'ROLE_ADMIN',
                    'USER' => 'ROLE_USER'
                ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
