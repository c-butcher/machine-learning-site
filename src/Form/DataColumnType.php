<?php

namespace App\Form;

use App\Entity\DataColumn;
use App\Form\DataTransformer\DataTypeTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DataColumnType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class);
        $builder->add('type', ChoiceType::class, [
            'choices' => [
                'Text'     => TextType::class,
                'Number'   => NumberType::class,
                'Date'     => DateType::class,
                'DateTime' => DateTimeType::class,
                'Boolean'  => CheckboxType::class,
                'Category' => ChoiceType::class
            ]
        ]);

        $builder->get('type')->addModelTransformer(new DataTypeTransformer());
    }

    /**
     * Set the default options for this form type.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DataColumn::class,
        ]);
    }
}
