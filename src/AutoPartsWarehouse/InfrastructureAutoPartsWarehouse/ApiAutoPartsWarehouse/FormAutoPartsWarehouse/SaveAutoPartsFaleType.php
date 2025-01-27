<?php

namespace App\AutoPartsWarehouse\InfrastructureAutoPartsWarehouse\ApiAutoPartsWarehouse\FormAutoPartsWarehouse;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
                        'extensions' => [
                            'xlsx',
                            'xml',
                            'csv',
                            'ods'
                        ],
                        'extensionsMessage' => 'Указано неверное разрешение файла
                        разрешение должно быть XLSX(Excel), XML, CSV, ODS'
                    ]),
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
