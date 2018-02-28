<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Product;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $data = [
            [
                "name" => "Iphone X",
                "description" => "Ceci est un iphone 7",
                "prix" => 1159.00
            ],
            [
                "name" => "SamSung galaxy A8",
                "description" => "Ceci est un SamSung galaxy A8",
                "prix" => 499.00
            ],
            [
                "name" => "SAMSUNG GALAXY S7",
                "description" => "Ceci est un SAMSUNG GALAXY S7",
                "prix" => 549.00
            ],
            [
                "name" => "SAMSUNG GALAXY J3",
                "description" => "Ceci est un SAMSUNG GALAXY J3",
                "prix" => 199.00
            ],
            [
                "name" => "SAMSUNG GALAXY S8",
                "description" => "Ceci est un SAMSUNG GALAXY S8",
                "prix" => 809.00
            ],
            [
                "name" => "HONOR 5X",
                "description" => "Ceci est un HONOR 5X",
                "prix" => 149.00
            ],
            [
                "name" => "SAMSUNG GALAXY S9",
                "description" => "Ceci est un SAMSUNG GALAXY S9",
                "prix" => 859.00
            ],
            [
                "name" => "BLACKBERRY TORCH 9860",
                "description" => "Ceci est un BLACKBERRY TORCH 9860",
                "prix" => 199.00
            ],
            [
                "name" => "BLACKBERRY 9300 CURVE",
                "description" => "Ceci est un BLACKBERRY 9300 CURVE",
                "prix" => 79.00
            ],
            [
                "name" => "BLACKBERRY BLACKBERRY DTEK50",
                "description" => "Ceci est un BLACKBERRY BLACKBERRY DTEK50",
                "prix" => 184.00
            ],
        ];


        foreach ($data as $value) {
            $product = new Product();
            $product->setName($value['name'])
            ->setDescription($value['description'])
            ->setPrix($value['prix'])
            ;

            $manager->persist($product);
        }

        $manager->flush();
    }
}
