<?php

namespace App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormCarBrands;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EditCarBrandsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('car_brand', TextType::class, [
                'label' => 'Марка',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-z\s\d]*$/i',
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
            ->add('button_car_brand', SubmitType::class, [
                'label' => 'Изменить'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
