<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Message;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $userId = $options['userId'];
        $builder
            ->add('recepient', EntityType::class, array(
                'label' => 'Recepient',
                'class' => User::class,
                'choice_label' => function ($user) {
                    return $user->getUserInfo()->getCompleteName();
                },
                'choice_value' => function (User $user = null) {
                    return $user ? $user->getId() : '';
                },
                'group_by' => function (User $user) {
                    return (new \ReflectionClass($user))->getShortName();
                },
                'multiple' => false,
                'expanded' => false,
                'attr' => ['class' => 'chosen-select'],
                'query_builder' => function (UserRepository $repo) use ($userId) {
                    return $repo->findNonAdminUsers($userId);
                },
            ))
            ->add('subject', TextType::class, array(
                'label' => 'Subject',
            ))
            ->add('message', TextareaType::class, array(
                'label' => 'Message',
                'attr' => ['rows' => 15, 'cols' => 10,],
            ))
            ->add('fileUpload', FileType::class, array(
                'label' => 'Attachment',
                'attr'=>[
                    'class' => 'form-control',
                    'required'   => false,
                ]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Message::class,
        ));
        $resolver->setRequired('userId');
    }
}
