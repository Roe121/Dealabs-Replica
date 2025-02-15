<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null, [
                'attr' => ['placeholder' => 'Email'],
                'label' => 'Email',
            ])
            // ->add('roles')
            ->add('password', null, [
                'attr' => ['placeholder' => 'Mot de passe'],
                'label' => 'Mot de passe',
            ])
            ->add('username', null, [
                'attr' => ['placeholder' => 'Nom d\'utilisateur'],
                'label' => 'Nom d\'utilisateur',
            ])
            ->add('image', null, [
                'attr' => ['placeholder' => 'Image'],
                'label' => 'Image',
            ])
            // ->add('createdAt', null, [
            //     'widget' => 'single_text'
            // ])
            // ->add('updatedAt', null, [
            //     'widget' => 'single_text'
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
