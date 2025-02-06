<?php

namespace App\DataFixtures;

use App\Entity\Merchant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class MerchantFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $merchants = [
            ['Amazon', 'https://www.amazon.com', 'amazon.png'],
            ['eBay', 'https://www.ebay.com', 'ebay.png'],
            ['AliExpress', 'https://www.aliexpress.com', 'aliexpress.png'],
            ['Fnac', 'https://www.fnac.com', 'fnac.png'],
            ['Cdiscount', 'https://www.cdiscount.com', 'cdiscount.png'],
        ];

        foreach ($merchants as $data) {
            $merchant = new Merchant();
            $merchant->setName($data[0]);
            $merchant->setWebsiteUrl($data[1]);
            $merchant->setLogo($data[2]);
            $merchant->setCreatedAt(new \DateTimeImmutable());
            $merchant->setEnable(true);

            $manager->persist($merchant);
            $this->addReference('merchant_' . $data[0], $merchant);
        }

        // Générer d'autres marchands aléatoires avec Faker
        for ($i = 0; $i < 10; $i++) {
            $merchant = new Merchant();
            $merchant->setName($faker->company);
            $merchant->setWebsiteUrl($faker->url);
            $merchant->setLogo(null);
            $merchant->setCreatedAt(new \DateTimeImmutable());
            $merchant->setEnable($faker->boolean(80)); // 80% de chance d'être activé

            $manager->persist($merchant);
        }

        $manager->flush();
    }
}
