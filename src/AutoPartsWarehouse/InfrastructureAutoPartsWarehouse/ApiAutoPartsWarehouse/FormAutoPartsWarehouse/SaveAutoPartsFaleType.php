<?php

namespace App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\FormAutoPartsWarehouse;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SaveAutoPartsFaleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file_save', FileType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Форма не может быть 
                    пустой'
                    ]),
                    new File([
                        'maxSize' => '64M',
                        'maxSizeMessage' => 'Максимальный размер файла не должен превышать 64м',
                        'mimeTypes' => [
                            'xlsx' => 'application/xml',
                            'xml' => 'application/xml',
                            'csv' => 'application/xml'
                        ],
                        'mimeTypesMessage' => 'The format is incorrect, only PDF allowed'
                    ]),
                ]
            ])
            ->add('file_extension', ChoiceType::class, [
                'choices'  => [
                    'XLSX(Excel)' => 'XLSX(Excel)',
                    'XML' => 'XML',
                    'CSV' => 'CSV'
                ]
            ])

            ->add('button_save_fale', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
