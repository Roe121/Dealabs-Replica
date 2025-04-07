<?php

namespace App\Tests\Vote;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use App\Entity\Deal;
use App\Entity\Merchant;
use Doctrine\ORM\EntityManagerInterface;

class VoteControllerTest extends WebTestCase
{
    public function testUserCanVoteForDeal(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $em = $container->get(EntityManagerInterface::class);

        $merchant = new Merchant();
        $merchant->setName('Test Merchant');
        $merchant->setCreatedAt(new \DateTimeImmutable());
        $merchant->setEnable(true);
        $em->persist($merchant);

        
        $user = new User();
        $user->setEmail('vote@test.com');
        $user->setPassword('password'); 
        $user->setUsername('votetest');
        $em->persist($user);

        $deal = new Deal();
        $deal->setName('Pizza à moitié prix !');
        $deal->setDescription('Super promo sur les pizzas');
        $deal->setPrice(5.99);
        $deal->setOriginalPrice(11.99);
        $deal->setUrl('https://example.com/deal/pizza');
        $deal->setMerchant($merchant);
        $deal->setUser($user); 



        $em->persist($deal);

        $em->flush();

        $client->loginUser($user); 

        $client->request('POST', '/vote/' . $deal->getId(), [], [], ['CONTENT_TYPE' => 'application/json'], json_encode(['type' => 1]));

        $this->assertResponseIsSuccessful(); // 200

        $voteRepo = $em->getRepository(\App\Entity\Vote::class);
        $votes = $voteRepo->findBy(['user' => $user, 'deal' => $deal]);

        $this->assertCount(1, $votes);
    }
}
