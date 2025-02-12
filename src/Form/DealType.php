<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Deal;
use App\Entity\Merchant;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DealType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            // ->add('createdAt', null, [
            //     'widget' => 'single_text'
            // ])
            // ->add('updatedAt', null, [
            //     'widget' => 'single_text'
            // ])
            ->add('price')
            // ->add('enable')
            ->add('originalPrice')
            ->add('url')
            ->add('image')
            ->add('startAt', null, [
                'widget' => 'single_text'
            ])
            ->add('expiredAt', null, [
                'widget' => 'single_text'
            ])
            // ->add('status')
            // ->add('hotScore')
            ->add('deliveryPrice')
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            // ->add('user', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id',
            // ])
            ->add('merchant', EntityType::class, [
                'class' => Merchant::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Deal::class,
        ]);
    }
}
