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
            ['username' => 'david', 'email' => 'david@example.com', 'password' => 'david'],
            ['username' => 'eve', 'email' => 'eve@example.com', 'password' => 'eve'],
            ['username' => 'frank', 'email' => 'frank@example.com', 'password' => 'frank'],
            ['username' => 'grace', 'email' => 'grace@example.com', 'password' => 'grace'],
            ['username' => 'hannah', 'email' => 'hannah@example.com', 'password' => 'hannah'],
            ['username' => 'ian', 'email' => 'ian@example.com', 'password' => 'ian'],
            ['username' => 'jack', 'email' => 'jack@example.com', 'password' => 'jack'],
            ['username' => 'kate', 'email' => 'kate@example.com', 'password' => 'kate'],
            ['username' => 'leo', 'email' => 'leo@example.com', 'password' => 'leo'],
            ['username' => 'mia', 'email' => 'mia@example.com', 'password' => 'mia'],
            ['username' => 'nathan', 'email' => 'nathan@example.com', 'password' => 'nathan'],
            ['username' => 'lezzarrami', 'email' => 'lezzarrami@gmail.com', 'password' => 'securepassword'],
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

            $this->addReference('user_' . ($index), $user);
            
        }

        $manager->flush();
    }
}
