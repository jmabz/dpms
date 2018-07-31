<?php

namespace App\Form;

use App\Entity\Clinic;
use App\Form\DoctorType;
use App\Form\DataTransformer\DateToStringTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClinicType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('clinicName', TextType::class, [
                'label' => 'Clinic Name'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Clinic Email'
            ])
            ->add('schedStart', TextType::class, [
                'label' => 'Opening Time',
                'attr' => ['class' => 'timepicker']
            ])
            ->add('schedEnd', TextType::class, [
                'label' => 'Closing Time',
                'attr' => ['class' => 'timepicker']
            ])
            ->add('doctors', CollectionType::class, [
                'label' => 'Doctors to add',
                'entry_type' => DoctorType::class,
                'entry_options' =>[ 'label' => false ],
                'allow_add' => true,
            ]);

        $builder
            ->get('schedStart')
            ->addModelTransformer(new DateToStringTransformer($builder->get('schedStart')));

        $builder
            ->get('schedEnd')
            ->addModelTransformer(new DateToStringTransformer($builder->get('schedEnd')));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Clinic::class,
        ));
    }
}
