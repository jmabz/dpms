<?php

namespace App\Form;

use App\Entity\Message;
use App\Entity\Reply;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReplyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->
            add('replyBody', TextareaType::class, array(
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Enter your reply here...',
                    'resize' => 'none',
                ),
            ));
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Reply::class,
        ));
    }
}