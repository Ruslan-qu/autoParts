<?php

namespace App\Counterparty\InfrastructureCounterparty\ApiCounterparty\FormCounterparty;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
            ->add('hidden', HiddenType::class)
            ->add('button_save_counterparty', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
