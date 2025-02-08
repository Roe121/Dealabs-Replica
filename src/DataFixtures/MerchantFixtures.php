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
            ['Amazon', 'https://www.amazon.com', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS5ckwJEfm-vgiiSHDGi4eqx8g5rKrNDluSMw&s'],
            ['eBay', 'https://www.ebay.com', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQEGzA7r4Oikd5HrfTWIKstXYJNIFRXygLBGQ&s'],
            ['AliExpress', 'https://www.aliexpress.com', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRSGy-LUgL5VExYn-rUAFINDvfm7Dj4itjitA&s'],
            ['Fnac', 'https://www.fnac.com', 'data:image/png;base64,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'],
            ['Cdiscount', 'https://www.cdiscount.com', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTMDHLGb39r6RTWOPMTT4U79KSTtKQXW6YhRg&s'],
        ];

        foreach ($merchants as $data) {
            $merchant = new Merchant();
            $merchant->setName($data[0]);
            $merchant->setWebsiteUrl($data[1]);
            $merchant->setImage($data[2]);
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
            $merchant->setImage(null);
            $merchant->setCreatedAt(new \DateTimeImmutable());
            $merchant->setEnable($faker->boolean(80)); // 80% de chance d'être activé

            $manager->persist($merchant);
        }

        $manager->flush();
    }
}
