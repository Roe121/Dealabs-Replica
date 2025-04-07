<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{

    public function testUserIsPersisted(): void
    {
        self::bootKernel();
        $em = static::getContainer()->get('doctrine.orm.entity_manager');

        $user = new User();
        $user->setEmail('integration@example.com');
        $user->setUsername('integrationuser');
        $user->setPassword('securepassword');

        $em->persist($user);
        $em->flush();

        $repo = $em->getRepository(User::class);
        $found = $repo->find($user->getId());

        $this->assertUserIntegration($found);
    }

    public function assertUserIntegration(User $user): void
    {
        $this->assertNotNull($user->getId());
        $this->assertEquals('integrationuser', $user->getUsername());
        $this->assertEquals('securepassword', $user->getPassword());
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
        $this->assertEquals('integration@example.com', $user->getEmail());

        $this->assertFalse($user->isVerified());
        $this->assertNull($user->getImage());
        $this->assertNotNull($user->getCreatedAt());
        $this->assertNull($user->getUpdatedAt());
    }
}
