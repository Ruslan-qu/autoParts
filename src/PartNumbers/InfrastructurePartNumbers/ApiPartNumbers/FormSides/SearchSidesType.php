<?php

namespace App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormSides;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchSidesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('side', TextType::class, [
                'label' => 'Сторона',
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
            ->add('button_side', SubmitType::class, [
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
