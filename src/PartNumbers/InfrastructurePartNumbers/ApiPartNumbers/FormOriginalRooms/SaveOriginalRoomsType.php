<?php

namespace App\PartNumbers\InfrastructurePartNumbers\ApiPartNumbers\FormOriginalRooms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SaveOriginalRoomsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('original_number', TextType::class, [
                'label' => 'Оригиналный номер',
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[\da-z]*$/i',
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
            ->add('original_manufacturer', TextType::class, [
                'label' => 'Производитель',
                'required' => false,
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[\da-z]*$/i',
                        //'match' => false,
                        'message' => 'Форма содержит 
                    недопустимые символы'
                    ])
                ]
            ])
            ->add('replacing_original_number', TextType::class, [
                'label' => 'Замена номера',
                'required' => false,
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[\da-z]*$/i',
                        //'match' => false,
                        'message' => 'Форма содержит 
                    недопустимые символы'
                    ])
                ]
            ])
            ->add('button_original_number', SubmitType::class, [
                'label' => 'Сохранить'
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
