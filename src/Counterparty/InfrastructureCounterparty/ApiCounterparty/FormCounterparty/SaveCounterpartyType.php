<?php

namespace App\Counterparty\InfrastructureCounterparty\ApiCounterparty\FormCounterparty;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SaveCounterpartyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('form_save_name_counterparty', TextType::class, [
                'label' => 'Поставщик',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[\da-z]*$/i',
                        //'match' => false,
                        'message' => 'Форма содержит недопустимые символы'
                    ]),
                    new NotBlank(
                        message: 'Форма содержит недопустимые символы',
                    )

                ]
            ])
            ->add('form_save_mail_counterparty', EmailType::class, [
                'label' => 'E-mail',
                'constraints' => [
                    new Email([
                        'message' => 'Форма содержит недопустимые символы'
                    ]),
                    new NotBlank([
                        'message' => 'Форма содержит недопустимые символы'
                    ]),
                ]
            ])
            ->add('form_save_manager_phone', TelType::class, [
                'label' => 'Тел. мен-ра',
                'constraints' => [
                    new Regex([
                        'pattern' => '/\+\d/',
                        //'match' => false,
                        'message' => 'Форма содержит недопустимые символы или нет знака +'
                    ]),
                    new Length([
                        'min' => '11',
                        'max' => '11',
                        'exactMessage' => 'Номер телефона содержит неверное количество цифр'
                    ])
                ]
            ])
            ->add('form_save_delivery_phone', TelType::class, [
                'label' => 'Тел. дос-ки',
                'constraints' => [
                    new Regex([
                        'pattern' => '/\+\d/',
                        //'match' => false,
                        'message' => 'Форма содержит недопустимые символы или нет знака +'
                    ]),
                    new Length([
                        'min' => '12',
                        'max' => '12',
                        'exactMessage' => 'Номер телефона содержит неверное количество цифр',
                    ])
                ]
            ])
            ->add('button_save_counterparty', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
