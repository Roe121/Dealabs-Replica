<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{


    public function load(ObjectManager $manager): void
    {
        $data = [
            ['name' => 'Technologie'],
            ['name' => 'Maison'],
            ['name' => 'Mode'],
            ['name' => 'Voyage'],
            ['name' => 'Sport'],
            ['name' => 'Culture'],
            ['name' => 'Beauté'],
            ['name' => 'Alimentation'],
            ['name' => 'Enfants'],
            ['name' => 'Animaux'],
        ];

        foreach ($data as $index => $item) {
            $category = new Category();
            $category->setName($item['name']);
            $this->addReference('category_' . $index, $category);
            $manager->persist($category);
        }



        $manager->flush();
    }
}
