<?php

namespace App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormAvailability;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EditAvailabilityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('in_stock', TextType::class, [
                'label' => 'Наличие',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[а-яё]*$/ui',
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
            ->add('id', HiddenType::class)
            ->add('button_in_stock', SubmitType::class, [
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
