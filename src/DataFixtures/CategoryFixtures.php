<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{

    public const CATEGORY_REFERENCE_1 = 'Technologie';
    public const CATEGORY_REFERENCE_2 = 'Home';

    public function load(ObjectManager $manager): void
    {
        $tech = new Category();
        $tech->setName('Technologie');
        $manager->persist($tech);
        
        // Ajouter une référence pour DealFixtures
        $this->addReference(self::CATEGORY_REFERENCE_1, $tech); // Store reference



        $home = new Category();
        $home->setName('Maison');
        $manager->persist($home);
        
        $this->addReference(self::CATEGORY_REFERENCE_2, $home);

        $manager->flush();
    }
}
