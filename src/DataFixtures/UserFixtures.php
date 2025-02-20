<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    
    public function __construct(private UserPasswordHasherInterface $passwordHasher) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 15; $i++) {
            $user = new User();

            $gender = $faker->randomElement(['men', 'women']);
            $imageIndex = $faker->numberBetween(1, 99); 
            $profileImage = "https://randomuser.me/api/portraits/{$gender}/{$imageIndex}.jpg";

            $user->setUsername($faker->userName);
            $user->setEmail($faker->unique()->safeEmail);
            $user->setRoles(['ROLE_USER']);
            $user->setImage($profileImage); 

            $plainPassword = 'p'; 
            $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);

            $manager->persist($user);

            $this->addReference('user_' . $i, $user);
        }

        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('lezzarrami@gmail.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setImage('https://randomuser.me/api/portraits/men/77.jpg');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin'));
        $admin->setIsVerified(true);
        $manager->persist($admin);

        $manager->flush();
    }
}
