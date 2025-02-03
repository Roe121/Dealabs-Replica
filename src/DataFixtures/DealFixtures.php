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

class DealFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Retrieve references to Category entities
        $category1 = $this->getReference(CategoryFixtures::CATEGORY_REFERENCE_1, Category::class);
        $category2 = $this->getReference(CategoryFixtures::CATEGORY_REFERENCE_2, Category::class);

        for ($i = 0; $i < 4; $i++) {
            $deal = new Deal();
            $deal->setName('Deal ' . $i+1);
            $deal->setDescription('Description ' . $i + 1);
            $deal->setPrice(mt_rand(10, 100));
            $deal->addCategory($i % 2 === 0 ? $category1 : $category2);
            $deal->setEnable(true);
            $manager->persist($deal);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class, // Ensure CategoryFixtures runs first
        ];
    }
}