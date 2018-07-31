<?php
namespace App\Form;

use App\Entity\Doctor;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DoctorType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('doctor', EntityType::class, [
                'label' => 'Doctor\'s Name:',
                'class' => Doctor::class,
                'choice_label' => function ($doctor) {
                    return $doctor->getUserInfo()->getFname() . ' ' . $doctor->getUserInfo()->getMname() . ' ' . $doctor->getUserInfo()->getLname();
                },
                'choice_value' => function (Doctor $doctor = null) {
                    return $doctor ? $doctor->getId() : '';
                },
                'attr' => ['class' => 'chosen-select']
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Doctor::class,
        ));
    }
}
