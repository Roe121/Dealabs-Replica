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
use Vich\UploaderBundle\Form\Type\VichImageType;

class DealType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
    ->add('name', null, [
        'attr' => ['placeholder' => 'Nom du deal'],
        'label' => 'Nom du deal',

    ])
    ->add('description', null, [
        'attr' => ['placeholder' => 'Description'],
        'label' => 'Description',

    ])
    ->add('price', null, [
        'attr' => ['placeholder' => 'Prix'],
        'label' => 'Prix',
    ])
    ->add('originalPrice', null, [
        'attr' => ['placeholder' => 'Prix original'],
        'label' => 'Prix original',
    ])
    ->add('url', null, [
        'attr' => ['placeholder' => 'Lien du deal'],
        'label' => 'Lien du deal',
    ])
    ->add('startAt', null, [
        'widget' => 'single_text',
        'label' => 'Date de début',
    ])
    ->add('expiredAt', null, [
        'widget' => 'single_text',
        'label' => 'Date d\'expiration',
    ])
    ->add('deliveryPrice', null, [
        'attr' => ['placeholder' => 'Frais de livraison'],
        'label' => 'Frais de livraison',
    ])
    ->add('category', EntityType::class, [
        'class' => Category::class,
        'label' => 'Catégorie',
        'choice_label' => 'name',
    ])
    ->add('merchant', EntityType::class, [
        'class' => Merchant::class,
        'label' => 'Marchand',
        'choice_label' => 'name',
    ])
    ->add('imageFile', VichImageType::class, [
        'required' => false,
        'label' => 'Image du deal',
        'allow_delete' => true,
        'download_uri' => true,
        'attr' => ['class' => 'form-control-file']
    ]);
}

}
