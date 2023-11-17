<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SuperAdminFixtures extends Fixture
{

    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher=$hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $superAdmin=$this->createSuperAdmin();
        
        $manager->persist($superAdmin);

        $manager->flush();
    }

    public function createSuperAdmin(): User
    {
        $superAdmin = new User();

        $superAdmin->setFirstName('Yakoub');
        
        $superAdmin->setLastName('Touati');

        $superAdmin->setEmail('healthy-food@gmail.com');

        $superAdmin->setRoles(['ROLE_USER','ROLE_ADMIN','ROLE_SUPER_ADMIN']);

        $passwordHased = $this->hasher->hashPassword($superAdmin,'Y@koubtouati123');

        $superAdmin->setPassword($passwordHased);

        $superAdmin->setVerifiedAt(new DateTimeImmutable());

        $superAdmin->setIsVerified(true);

        return $superAdmin;

    }

}