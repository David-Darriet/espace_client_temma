<?php

namespace App\DataFixtures;

use App\Entity\File;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    protected $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $usernames = ['alice', 'bob', 'charles'];
        $users = [];
        foreach ($usernames as $username) {
            $user = new User;
            $user
                ->setFirstname($username)
                ->setLastname("TestLastname")
                ->setEmail($username . '@test.fr')
                ->setPassword($this->userPasswordHasher->hashPassword($user, 'password'))
                ->setIsAdmin(false)
                ->setIsVerified(true);
            $manager->persist($user);
            $users[$username] = $user;

            $files = ["assets/Test A.pdf", "assets/test_excel.xlsx"];

            foreach ($files as $filePath) {
                $file = new File;
                $file
                    ->setPath($filePath)
                    ->setFormat("pdf")
                    ->setUser($user)
                    ->setCreatedAt(new DateTimeImmutable('2022-01-01'));
                $manager->persist($file);
            }
        }

        $admin = new User;
        $admin
            ->setFirstname("Admin")
            ->setLastname("Admin")
            ->setEmail( 'admin@admin.fr')
            ->setPassword($this->userPasswordHasher->hashPassword($admin, 'password'))
            ->setIsAdmin(true)
            ->setIsVerified(true);
        $manager->persist($admin);

        $userWithoutFile = new User;
        $userWithoutFile
            ->setFirstname("Jean")
            ->setLastname("Bon")
            ->setEmail('jeanbon@test.fr')
            ->setPassword($this->userPasswordHasher->hashPassword($userWithoutFile, 'password'))
            ->setIsAdmin(false)
            ->setIsVerified(true);
        $manager->persist($userWithoutFile);

        $manager->flush();
    }
}
