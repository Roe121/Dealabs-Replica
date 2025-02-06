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

    $dealsData = [
        ['PlayStation 5 Slim + 2ème manette', 'Nouvelle version plus fine de la PS5 avec une manette supplémentaire.', 499.99, 549.99, 'https://www.example.com/deal/0','https://media.direct.playstation.com/is/image/psdglobal/PS5-Disc-Slim-Hero1-Box-and-console-EU-V2'],
        ['Samsung Galaxy S23 Ultra 256Go', 'Smartphone haut de gamme avec écran 120Hz et capteur 200MP.', 999.99, 1299.99, 'https://www.example.com/deal/1','https://www.cdiscount.com/pdt2/2/4/2/2/700x700/sam8806094733242/rw/samsung-galaxy-s23-ultra-256go-noir.jpg'],
        ['Téléviseur OLED LG 55" 4K', 'TV OLED avec Dolby Vision et 120Hz pour une immersion totale.', 1099.99, 1499.99, 'https://www.example.com/deal/2','https://m.media-amazon.com/images/I/71UgknSbM4L.jpg'],
        ['Apple MacBook Air M2 13"', 'PC portable ultrafin avec puce M2 et écran Retina.', 1149.99, 1399.99, 'https://www.example.com/deal/3','https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT95SAb6py9dydN3tEKKrjtQK03JUwBvzX2UQ&s'],
        ['Casque Sony WH-1000XM5', 'Casque Bluetooth avec réduction de bruit active et autonomie de 30h.', 299.99, 399.99, 'https://www.example.com/deal/4','https://www.sony.fr/commerceapi/medias/1.jpg?context=bWFzdGVyfHJvb3R8MTE1NzIwfGltYWdlL2pwZWd8YUdKaUwyaGtaUzg1TVRFNE1qQXhORFEyTkRNd0x6RXVhbkJufDliNzU1NTRmZDUzMmI3YTg2OTM1ZjE3NGZjNmFiYTVmMDc2ZjI0YTdkNmQ0NjgyNWJkMDczYzA1MTQyZTJjYTg&trf=trim'],
        ['Aspirateur robot Roomba i7+', 'Aspirateur intelligent avec vidage automatique.', 599.99, 799.99, 'https://www.example.com/deal/5','https://static.fnac-static.com/multimedia/Images/FR/MDM/39/38/a6/10893369/1520-5/tsp20241129184415/Aspirateur-robot-Irobot-Roomba-i7-avec-sa-base-autovidage-CleanBase-Noir.jpg'],
        ['Montre connectée Apple Watch Series 9', 'Modèle GPS + Cellular, autonomie améliorée.', 429.99, 529.99, 'https://www.example.com/deal/6','https://m.media-amazon.com/images/I/71DA1dkH9HL._AC_UF1000,1000_QL80_.jpg'],
        ['Nintendo Switch OLED + Zelda TOTK', 'Pack console OLED avec le jeu Zelda : Tears of the Kingdom.', 359.99, 399.99, 'https://www.example.com/deal/7','https://m.media-amazon.com/images/I/71hBz3ZtiuL.jpg'],
        ['Écran gaming 27" 165Hz ASUS', 'Moniteur IPS avec faible latence pour les gamers.', 249.99, 329.99, 'https://www.example.com/deal/8','https://m.media-amazon.com/images/I/813Mk7nW5pL.jpg'],
        ['PC Portable Gaming ACER NITRO i7 12gen RTX 4060', 'Laptop gamer avec processeur i7 et carte graphique RTX 4060.', 1299.99, 1599.99, 'https://www.example.com/deal/9','https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQub9SI8GEShl3WW9bDCx_EXoBUQbQwZgAIrQ&s'],
        ['Barre de son Bose 700', 'Son immersif avec Dolby Atmos et design premium.', 649.99, 799.99, 'https://www.example.com/deal/10','https://static.fnac-static.com/multimedia/Images/FR/NR/ce/6d/11/17919438/1505-1/tsp20240528104044/Barre-de-son-Bose-Smart-Ultra-Soundbar-Dolby-Atmos-Noir-Module-de-baes-Bose-700-Noir-Paire-d-enceintes-Arriere-Bose-Surround-700-Noir.jpg'],
    ];

    foreach ($dealsData as $index => [$name, $description, $price, $originalPrice, $dealUrl, $imageUrl]) {
        $deal = new Deal();
        $deal->setName($name);
        $deal->setDescription($description);
        $deal->setPrice($price);
        $deal->setOriginalPrice($originalPrice);
        $deal->addCategory($index % 2 === 0 ? $category1 : $category2);
        $deal->setEnable(true);
        $deal->setDealUrl($dealUrl);
        $deal->setImage($imageUrl);
        $deal->setStatus(DealStatus::ACTIVE);
        $deal->setHotScore(mt_rand(10, 100));
        $deal->setMerchant($this->getReference('merchant_Amazon', Merchant::class));
        $userReference = $this->getReference('user_' . (($index % 2) + 1), User::class);
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
            CategoryFixtures::class, // Ensure CategoryFixtures runs first
            MerchantFixtures::class,
        ];
    }
}