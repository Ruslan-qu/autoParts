<?php

namespace App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\FormAutoPartsWarehouse;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SaveAutoPartsFaleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file_save', FileType::class, [
                //'label' => 'Загрузка файла',
                /* 'constraints' => [
                new NotBlank([
                    'message' => 'Форма не может быть 
                    пустой'
                ]),
                new Regex([
                    'pattern' => '/^[\da-z]*$/i',
                    //'match' => false,
                    'message' => 'Форма содержит 
                    недопустимые символы'
                ]),
            ]*/])
            ->add('button_save_fale', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
