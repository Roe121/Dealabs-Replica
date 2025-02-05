<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Text;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            EmailField::new('email', 'Email'),
            TextField::new('password', 'Mot de passe') // ✅ Ajout du champ mot de passe
                ->setRequired($pageName === 'new') // Obligatoire seulement lors de la création
                ->onlyOnForms(), // Visible uniquement dans le formulaire            TextField::new('username', 'Nom d\'utilisateur'),
            TextField::new('username', 'Nom d\'utilisateur'),
            ChoiceField::new('roles', 'Rôles')
                ->setChoices([
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                    'Super Admin' => 'ROLE_SUPER_ADMIN'
                ])
                ->allowMultipleChoices(), // Permet de sélectionner plusieurs rôles
        ];
    }


    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof User) {
            return;
        }

        // ✅ Hasher le mot de passe avant de l'enregistrer
        $plainPassword = $entityInstance->getPassword();
        if ($plainPassword) {
            $hashedPassword = $this->passwordHasher->hashPassword($entityInstance, $plainPassword);
            $entityInstance->setPassword($hashedPassword);
        }

        $entityInstance->setRoles(array_values($entityInstance->getRoles())); // ✅ Enregistrer les rôles correctement

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof User) {
            return;
        }

        // ✅ Hasher le mot de passe seulement s'il est modifié
        $plainPassword = $entityInstance->getPassword();
        if ($plainPassword && strlen($plainPassword) < 60) { // Vérifie si ce n'est pas déjà un hash
            $hashedPassword = $this->passwordHasher->hashPassword($entityInstance, $plainPassword);
            $entityInstance->setPassword($hashedPassword);
        }

        $entityInstance->setRoles(array_values($entityInstance->getRoles())); // ✅ Enregistrer les rôles correctement

        parent::updateEntity($entityManager, $entityInstance);
    }
}
