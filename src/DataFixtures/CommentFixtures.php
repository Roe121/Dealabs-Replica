<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Deal;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $comment = new Comment();
            $comment->setUser($this->getReference('user_' . rand(0, 4), User::class));
            $comment->setDeal($this->getReference('deal_' . rand(0, 4), Deal::class));
            $comment->setContent($faker->realText(200));
            $comment->setPositiveVotes($faker->numberBetween(0, 100));
            $comment->setNegativeVotes($faker->numberBetween(0, 100));
            $comment->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($comment);

        }

        $manager->flush();

    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            DealFixtures::class,
        ];
    }
}
