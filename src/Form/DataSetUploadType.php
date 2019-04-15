<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class DataSetUploadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('uploadedFile', FileType::class)
            ->add('type', ChoiceType::class, [
                'placeholder' => 'Choose a Type',
                'choices' => [
                    'Excel Spreadsheet (XSLX)'     => 'xslx',
                    'Comma Separated Values (CSV)' => 'csv'
                ]
            ])
            ->add('hasColumnLabels', CheckboxType::class, [
                'required' => false
            ])
        ;
    }
}
