<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Movies;


class MoviesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create("FR-fr");
        for($i=0 ; $i < 20; $i++) {
            $movies = new Movies();
            $movies->setNom($faker->state)
                ->setSynopsis($faker->text)
                ->setType("film");
            $manager->persist($movies);
        }
        $manager->flush();
    }
}
