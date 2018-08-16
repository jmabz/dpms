<?php

namespace App\Form;

use App\Entity\UserInfo;
use App\Form\DataTransformer\DateToStringTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserInfoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fname', TextType::class, [
                'label' => 'First Name',
            ])
            ->add('mname', TextType::class, [
                'label' => 'Middle Name',
            ])
            ->add('lname', TextType::class, [
                'label' => 'Family Name',
            ])
            ->add('suffix', TextType::class, [
                'label' => 'Suffix',
            ])
            ->add('gender', ChoiceType::class, [
                    'choices' => [
                        'Select Gender' => null,
                        'Male' => 'Male',
                        'Female' => 'Female'
                    ],
                    'label' => 'Gender:',
                    'attr' => ['class' => 'form-control']
                    ])
            ->add('civil_status', ChoiceType::class, [
                    'choices' => [
                        'Civil Status' => null,
                        'Single' => 'Single',
                        'Married' => 'Married',
                        'Widowed' => 'Widowed',
                        'Annulled' => 'Annulled'
                    ],
                    'attr' => ['class' => 'form-control']
                    ])
            ->add('address', TextType::class, [
                'label' => 'Address',
            ])
            ->add('birthDate', TextType::class, [
                'label' => 'Birth Date',
                'attr' => [
                    'class' => 'datepicker',
                ],
            ])
            ->add('fileUpload', FileType::class, array('attr'=>[
                'class' => 'form-control',
                'required'   => false,
            ]));

        $builder
            ->get('birthDate')
            ->addModelTransformer(new DateToStringTransformer($builder->get('birthDate')));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => UserInfo::class,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'userInfo';
    }
}
