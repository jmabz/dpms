<?php

namespace App\Form;

use App\Entity\Appointment;
use App\Entity\Doctor;
use App\Form\DataTransformer\DateToStringTransformer;
use App\Repository\DoctorRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('appointmentDate', TextType::class, [
                'label' => 'Preferred Appointment Date',
                'attr' => [
                    'class' => 'datepicker'
                ]])
            ->add('reason', TextareaType::class, [
                'label' => 'Reason for Appointment',
            ])
            ->add('doctor', EntityType::class, [
                'label' => 'Doctor to Contact',
                'class' => Doctor::class,
                'choice_label' => function ($doctor) {
                    return $doctor->getUserInfo()->getCompleteName();
                },
                'choice_value' => function (Doctor $doctor = null) {
                    return $doctor ? $doctor->getId() : '';
                },
                'multiple' => false,
                'expanded' => false,
                'attr' => ['class' => 'chosen-select'],
                // 'query_builder' => function (DoctorRepository $repo) use ($clinicId, $addMode) {
                //     return $addMode ? $repo->findDoctorsNotInClinic($clinicId) : $repo->findDoctorsInClinic($clinicId);
                // }
            ])
            ;
            $builder
            ->get('appointmentDate')
            ->addModelTransformer(new DateToStringTransformer($builder->get('appointmentDate')));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
        ]);
    }
}
