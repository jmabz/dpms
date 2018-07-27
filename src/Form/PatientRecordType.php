<?php

namespace App\Form;

use App\Entity\DiagnosisCategory;
use App\Entity\Doctor;
use App\Entity\Patient;
use App\Entity\PatientRecord;

use App\Entity\Form\DataTransformer\DateToStringTransformer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatientRecordType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('checkupDate', TextType::class, [
                'label' => 'Checkup Date',
                'attr' => [
                    'class' => 'datepicker'
                ],
            ])
            ->add('checkupReason', TextareaType::class, [
                'label' => 'Checkup Reason',
            ])
            ->add('diagnosis', TextareaType::class, [
                'label' => 'Diagnosis',
            ])
            ->add('diagnosiscategory', EntityType::class, [
                'label' => 'Diagnosis Category',
                'class' => DiagnosisCategory::class,
                'choice_label' => function ($diagnosiscategory) {
                    return $diagnosiscategory->getDiagnosisName();
                },
                'choice_value' => function (DiagnosisCategory $diagnosiscategory = null) {
                    return $diagnosiscategory ? $diagnosiscategory->getId() : '';
                }
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => PatientRecord::class,
        ));
    }
}
