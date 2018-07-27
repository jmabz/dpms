<?php

namespace App\Form;

use App\Entity\UserInfo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
                    'Male' => 'Male',
                    'Female' => 'Female',
                ],
                'label' => 'Gender',
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('civilStatus', ChoiceType::class, [
                'choices' => [
                    'Single' => 'Single',
                    'Married' => 'Married',
                    'Widowed' => 'Widowed',
                ],
                'label' => 'Civil Status',
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('address', TextType::class, [
                'label' => 'Address',
            ])
            ->add('birthDate', TextType::class, [
                'label' => 'Birth Date',
            ]);
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
