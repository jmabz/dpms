<?php

namespace App\Form;

use App\Entity\AccreditationInfo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccreditationInfoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('accreditationCode', TextType::class, [
                    'label' => 'Accreditation Code',
                ])
            ->add('accreditationDate', TextType::class, [
                    'label' => 'Accreditation Date',
                ])
            ->add('accreditationExpDate', TextType::class, [
                    'label' => 'Accreditation Expiry Date'
                ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => AccreditationInfo::class,
        ));
    }
}
