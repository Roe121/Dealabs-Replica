<?php

namespace App\DataFixtures;

// src/DataFixtures/DealFixtures.php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Deal;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\DataFixtures\CategoryFixtures;
use App\Entity\Merchant;
use App\Entity\User;
use App\Enum\DealStatus;

class DealFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Retrieve references to Category entities
        $category1 = $this->getReference(CategoryFixtures::CATEGORY_REFERENCE_1, Category::class);
        $category2 = $this->getReference(CategoryFixtures::CATEGORY_REFERENCE_2, Category::class);

        for ($i = 0; $i < 11; $i++) {
            $deal = new Deal();
            $deal->setName('Deal ' . $i+1);
            $deal->setDescription('Description ' . $i + 1);
            $deal->setPrice(mt_rand(10, 100));
            $deal->addCategory($i % 2 === 0 ? $category1 : $category2);
            $deal->setEnable(true);

            $deal->setOriginalPrice(mt_rand(100, 200));
            $deal->setDealUrl('https://www.example.com/deal/' . $i);
            $deal->setImage('https://picsum.photos/id/' . rand(1,1000) . '/400/300');
            $deal->setStatus(DealStatus::ACTIVE);
            $deal->setHotScore(mt_rand(10, 100));
            $deal->setMerchant($this->getReference('merchant_Amazon', Merchant::class));
            $userReference = $this->getReference('user_' . (($i % 2) + 1), User::class);
            $this->addReference('deal_' . $i, $deal);
            $deal->setUser($userReference);

            $manager->persist($deal);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CategoryFixtures::class, // Ensure CategoryFixtures runs first
            MerchantFixtures::class,
        ];
    }
}