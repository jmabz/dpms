<?php

namespace App\Form;

use App\Entity\Clinic;
use App\Entity\Doctor;
use App\Form\DoctorType;
use App\Repository\DoctorRepository;
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
        $clinicId = $options['clinicId'];
        $addMode = $options['addMode'];
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
            // ->add('doctors', CollectionType::class, [
            //     'label' => 'Doctors to Add:',
            //     'entry_type' => DoctorType::class,
            //     'allow_add' => true,
            //     'allow_delete' => true,
            //     'by_reference' => false,
            //     'prototype' => true,
            //     // 'entry_options' => [
            //     //     'prototype' => true,
            //     //     'allow_add' => true,
            //     //     'allow_delete' => true,
            //     //     'class' => Doctor::class,
            //     // ],
            //     // 'attr' => [
            //     //     ['class' => 'js-clinic-doctor-wrapper']
            //     // ],
            //     // 'entry_options' => [
            //     //     'attr' => ['class' => 'chosen-select js-clinic-doctor-item']
            //     // ],
            // ])
            ->add('doctors', EntityType::class, [
                'class' => Doctor::class,
                'choice_label' => function ($doctor) {
                    return $doctor->getUserInfo()->getFname() . ' ' . $doctor->getUserInfo()->getMname() . ' ' . $doctor->getUserInfo()->getLname();
                },
                'choice_value' => function (Doctor $doctor = null) {
                    return $doctor ? $doctor->getId() : '';
                },
                'multiple' => true,
                'expanded' => false,
                'query_builder' => function (DoctorRepository $repo) use ($clinicId, $addMode) {
                    return $addMode ? $repo->findDoctorsNotInClinic($clinicId) : $repo->findDoctorsInClinic($clinicId);
                }
            ])
            ;

        $builder
            ->get('schedStart')
            ->addModelTransformer(new DateToStringTransformer($builder->get('schedStart')));

        $builder
            ->get('schedEnd')
            ->addModelTransformer(new DateToStringTransformer($builder->get('schedEnd')));

        if ($options['editDoctors']) {
            $builder
                ->remove('schedEnd')
                ->remove('schedStart')
                ->remove('email')
                ->remove('clinicName')
            ;
        } elseif ($options['editClinic']) {
            $builder
                ->remove('doctors');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Clinic::class,
            'editDoctors' => false,
            'editClinic' => false,
        ));
        $resolver->setRequired('clinicId');
        $resolver->setRequired('addMode');
    }
}
