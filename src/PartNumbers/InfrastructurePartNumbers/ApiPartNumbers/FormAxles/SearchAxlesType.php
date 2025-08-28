<?php

namespace App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormAxles;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchAxlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('axle', TextType::class, [
                'label' => 'Оси',
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
            ->add('button_axle', SubmitType::class, [
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
