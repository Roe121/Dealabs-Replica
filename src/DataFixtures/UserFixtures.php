<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 15; $i++) {
            $user = new User();

            $gender = $faker->randomElement(['men', 'women']);
            $imageIndex = $faker->numberBetween(1, 99); // randomuser images go from 1 to 99
            $profileImage = "https://randomuser.me/api/portraits/{$gender}/{$imageIndex}.jpg";

            $user->setUsername($faker->userName);
            $user->setEmail($faker->unique()->safeEmail);
            $user->setRoles(['ROLE_USER']);
            $user->setImage($profileImage); // Assurez-vous que l'entité User a un champ profileImage

            // Hash du mot de passe
            $plainPassword = 'password'; // Exemple de mot de passe simple
            $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);

            $manager->persist($user);

            $this->addReference('user_' . $i, $user);
        }

        $manager->flush();
    }
}
