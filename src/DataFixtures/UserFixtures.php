<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
        // Création de 3 utilisateurs
        $usersData = [
            ['username' => 'alice', 'email' => 'alice@example.com', 'password' => 'alice'],
            ['username' => 'bob', 'email' => 'bob@example.com', 'password' => 'bob'],
            ['username' => 'charlie', 'email' => 'charlie@example.com', 'password' => 'charlie'],
        ];

        foreach ($usersData as $index => $userData) {
            $user = new User();
            $user->setUsername($userData['username']);
            $user->setEmail($userData['email']);
            $user->setRoles(['ROLE_USER']);
            
            // Hash du mot de passe
            $hashedPassword = $this->passwordHasher->hashPassword($user, $userData['password']);
            $user->setPassword($hashedPassword);

            $manager->persist($user);

            // Sauvegarde de la référence pour les deals
            if ($index < 2) {
                $this->addReference('user_' . ($index + 1), $user);
            }
        }

        $manager->flush();
    }
}
