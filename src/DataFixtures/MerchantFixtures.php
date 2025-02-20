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
        $i = 0;

        $merchants = [
            ['Amazon', 'https://www.amazon.com', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS5ckwJEfm-vgiiSHDGi4eqx8g5rKrNDluSMw&s'],
            ['eBay', 'https://www.ebay.com', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQEGzA7r4Oikd5HrfTWIKstXYJNIFRXygLBGQ&s'],
            ['AliExpress', 'https://www.aliexpress.com', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRSGy-LUgL5VExYn-rUAFINDvfm7Dj4itjitA&s'],
            ['Fnac', 'https://www.fnac.com', 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/2e/Fnac_Logo.svg/499px-Fnac_Logo.svg.png'],
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
            $this->addReference('merchant_' . $i, $merchant);
            $i++;
        }

        // Générer d'autres marchands aléatoires avec Faker
        for ($i = 0; $i < 10; $i++) {
            $merchant = new Merchant();
            $merchant->setName($faker->company);
            $merchant->setWebsiteUrl($faker->url);
            $merchant->setImage(null);
            $merchant->setCreatedAt(new \DateTimeImmutable());
            $merchant->setEnable($faker->boolean(80)); 

            $manager->persist($merchant);
        }

        $manager->flush();
    }
}
