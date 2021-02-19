<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\Departement;
use App\Entity\Fixtures;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture

{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create("FR-fr");
        $user = new User();
        $user->setEmail('admin@admin.fr')
            ->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'nouveaupass76'
            ))
            ->setRoles(['ROLE_ADMIN']);
            $manager->persist($user);  
        $user2 = new User();
        $user2->setEmail('blabla@test.fr')
            ->setPassword($this->passwordEncoder->encodePassword(
                $user2,
                'nouveaupass76380'
            ))
            ->setRoles(['ROLE_USER']);
        $manager->persist($user2);
        $manager->flush();
    }
}
