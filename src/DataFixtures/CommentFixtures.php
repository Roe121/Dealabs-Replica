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
        $comments = [];

        for ($i = 0; $i < 40; $i++) {
            $comment = new Comment();
            $comment->setUser($this->getReference('user_' . rand(0, 14), User::class));
            $comment->setDeal($this->getReference('deal_' . rand(0, 10), Deal::class));
            $comment->setContent($faker->realText(200));
            $comment->setPositiveVotes($faker->numberBetween(0, 20));
            $comment->setNegativeVotes($faker->numberBetween(0, 20));
            $comment->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($comment);
            $comments[] = $comment;
        }

        for ($i = 0; $i < 20; $i++) {
            if (!empty($comments)) {
                $parentComment = $comments[array_rand($comments)]; 
    
                $reply = new Comment();
                $reply->setUser($this->getReference('user_' . rand(0, 14), User::class));
                $reply->setDeal($parentComment->getDeal()); 
                $reply->setContent($faker->realText(150));
                $reply->setPositiveVotes($faker->numberBetween(0, 10));
                $reply->setNegativeVotes($faker->numberBetween(0, 10));
                $reply->setCreatedAt(new \DateTimeImmutable());
                $reply->setParent($parentComment); 
    
                $manager->persist($reply);
            }
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
