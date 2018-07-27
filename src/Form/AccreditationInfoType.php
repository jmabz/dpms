<?php

namespace App\Form;

use App\Entity\AccreditationInfo;
use App\Form\DataTransformer\DateToStringTransformer;
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

        $builder
            ->get('accreditationDate')
            ->addModelTransformer(new DateToStringTransformer($builder->get('accreditationDate')));
        $builder
            ->get('accreditationExpDate')
            ->addModelTransformer(new DateToStringTransformer($builder->get('accreditationExpDate')));
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
