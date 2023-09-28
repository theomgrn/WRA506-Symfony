<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixtures extends Fixture
{
    public function __construct(protected UserPasswordHasherInterface $passwordHasherInterface)
    {

    }
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('user@mail.com');
        $user->setPassword($this->passwordHasherInterface->hashPassword($user, 'test'));
        $manager->persist($user);
        $manager->flush();
    }
}
