<?php

namespace App\Form;

use App\Entity\Appointment;
use App\Entity\Doctor;
use App\Form\DataTransformer\DateToStringTransformer;
use App\Repository\DoctorRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppointmentAcceptanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('appointmentStatus', ChoiceType::class, [
                'choices' => [
                    'Select Decision' => null,
                    'Accept' => 'Accepted',
                    'Decline' => 'Declined'
                ],
                'label' => 'Status:',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
        ]);
    }
}
