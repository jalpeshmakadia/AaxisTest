<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct (private UserPasswordHasherInterface $passwordHasher)
    {
    }
    
    public function load(ObjectManager $manager): void
    {
         $user = new User();
         $user->setEmail('admin@admin.com');
         $user->setPassword($this->passwordHasher->hashPassword($user, 'Admin@123'));
         $user->setToken(bin2hex(random_bytes(18)));
         $user->setRoles(['ROLE_USER']);
         $manager->persist($user);

        $manager->flush();
    }
}
