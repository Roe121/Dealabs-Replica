<?php

namespace App\Controller\Admin;

use App\Entity\Deal;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DealCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Deal::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            TextareaField ::new('description'),
            DateTimeField::new('createdAt')->hideOnForm(),
            DateTimeField::new('updatedAt')->hideOnForm(),
            MoneyField::new('price')->setCurrency('EUR')->setStoredAsCents(false),

            AssociationField::new('category')
            ->setFormTypeOption('choice_label', 'name')
            ->formatValue(function ($value, $entity) {
                return $entity->getCategory() ? $entity->getCategory()->getName() : '';
            }),
        ];
    }
    
}
