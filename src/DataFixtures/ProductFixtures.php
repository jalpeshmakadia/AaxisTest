<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class ProductFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i<=2; $i++) {
            $product = new Product();
            $product->setSku('SKU'.$i);
            $product->setProductName('Product '.$i);
            $product->setDescription('Sample Product '.$i);
            $manager->persist($product);
            $manager->flush();
        }

    }
}
