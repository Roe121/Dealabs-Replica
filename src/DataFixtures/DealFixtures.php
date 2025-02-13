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
use App\Enum\DealStatusEnum;

class DealFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
{
    // Retrieve references to Category entities
    $category1 = $this->getReference(CategoryFixtures::CATEGORY_REFERENCE_1, Category::class);
    $category2 = $this->getReference(CategoryFixtures::CATEGORY_REFERENCE_2, Category::class);

    $dealsData = [
        ['PlayStation 5 Slim + 2ème manette', 'Nouvelle version plus fine de la PS5 avec une manette supplémentaire.', 499.99, 549.99, 'https://www.example.com/deal/0','ps5.jpg'],
        ['Samsung Galaxy S23 Ultra 256Go', 'Smartphone haut de gamme avec écran 120Hz et capteur 200MP.', 999.99, 1299.99,'https://www.example.com/deal/0', 'sgs3.jpg'],
        ['Téléviseur OLED LG 55" 4K', 'TV OLED avec Dolby Vision et 120Hz pour une immersion totale.', 1099.99, 1499.99,'https://www.example.com/deal/0', 'TV-OLED.jpg'],
        ['Apple MacBook Air M2 13"', 'PC portable ultrafin avec puce M2 et écran Retina.', 1149.99, 1399.99, 'https://www.example.com/deal/3','Apple-MacBook-Air-M2-13.jpg'],
        ['Casque Sony WH-1000XM5', 'Casque Bluetooth avec réduction de bruit active et autonomie de 30h.', 299.99, 399.99, 'https://www.example.com/deal/4','cswh.jpg'],
        ['Aspirateur robot Roomba i7+', 'Aspirateur intelligent avec vidage automatique.', 599.99, 799.99, 'https://www.example.com/deal/5','EMEA_i7plus_m6g_3.jpg'],
        ['Montre connectée Apple Watch Series 9', 'Modèle GPS + Cellular, autonomie améliorée.', 429.99, 529.99, 'https://www.example.com/deal/6','LD0006065182_0006065187.jpg'],
        ['Nintendo Switch OLED + Zelda TOTK', 'Pack console OLED avec le jeu Zelda : Tears of the Kingdom.', 359.99, 399.99, 'https://www.example.com/deal/7','nintendo-switch-oled-zelda-edition.jpg'],
        ['Écran gaming 27" 165Hz ASUS', 'Moniteur IPS avec faible latence pour les gamers.', 249.99, 329.99, 'https://www.example.com/deal/8','8157lxvy2XL.jpg'],
        ['PC Portable Gaming ACER NITRO i7 12gen RTX 4060', 'Laptop gamer avec processeur i7 et carte graphique RTX 4060.', 1299.99, 1599.99, 'https://www.example.com/deal/9','acer-nitro.jpg'],
        ['Barre de son Bose 700', 'Son immersif avec Dolby Atmos et design premium.', 649.99, 799.99, 'https://www.example.com/deal/10','barre-son.jpg'],
    ];

    foreach ($dealsData as $index => [$name, $description, $price, $originalPrice, $url, $imageUrl]) {
        $deal = new Deal();
        $deal->setName($name);
        $deal->setDescription($description);
        $deal->setPrice($price);
        $deal->setOriginalPrice($originalPrice);
        $deal->addCategory($index % 2 === 0 ? $category1 : $category2);
        $deal->setEnable(true);
        $deal->setUrl($url);
        $deal->setImage($imageUrl);
        $deal->setStatus(DealStatusEnum::ACTIVE);
        $deal->setHotScore(mt_rand(10, 100));
        $deal->setDeliveryPrice($index % 2 === 0 ? 0 : rand(1, 4));
        $deal->setMerchant($this->getReference('merchant_' . rand(0, 4), Merchant::class));
        $userReference = $this->getReference('user_' .rand(0,8), User::class);
        $deal->setUser($userReference);
        $this->addReference('deal_' . $index, $deal);

        $manager->persist($deal);
    }

    $manager->flush();
}

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CategoryFixtures::class, 
            MerchantFixtures::class,
        ];
    }
}