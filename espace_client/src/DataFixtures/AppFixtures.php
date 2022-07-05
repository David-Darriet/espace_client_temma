<?php

namespace App\DataFixtures;

use App\Entity\Category;
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
        $categories = ['Factures', 'Devis', 'Maintenance', "Données machines", "Echanges"];
        foreach ($categories as $category) {
            $cat = new Category;
            $cat->setLabel($category);
            $manager->persist($cat);
        }
        $usernames = ['albert', 'bob', 'charles'];
        $users = [];
        foreach ($usernames as $username) {
            $user = new User;
            $user
                ->setFirstname($username)
                ->setLastname("TestLastname")
                ->setEmail($username . '@test.fr')
                ->setPassword($this->userPasswordHasher->hashPassword($user, 'password'))
                ->setIsVerified(true)
                ->setCivility("Monsieur")
                ->setLogin("test-" . $username[0] . $username[1])
                ->setIsAdmin(false)
                ->setEnterprise($username)
                ->setRoles(['ROLE_USER']);
            $manager->persist($user);
            $users[$username] = $user;

            $files = ["assets/Test A.pdf", "assets/test_excel.xlsx"];

            foreach ($files as $filePath) {
                $file = new File;
                $file
                    ->setPath($filePath)
                    ->setFormat("pdf")
                    ->setUser($user)
                    ->setName($filePath)
                    ->setCreatedAt(new DateTimeImmutable('2022-01-01'));
                $manager->persist($file);
            }
        }

        $admin = new User;
        $admin
            ->setFirstname("Admin")
            ->setLastname("Admin")
            ->setEmail('admin@admin.fr')
            ->setPassword($this->userPasswordHasher->hashPassword($admin, 'password'))
            ->setIsVerified(true)
            ->setCivility("Monsieur")
            ->setLogin("admin")
            ->setEnterprise("Temma")
            ->setIsAdmin(true)
            ->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $userWithoutFile = new User;
        $userWithoutFile
            ->setFirstname("Jean")
            ->setLastname("Bon")
            ->setEmail('jeanbon@test.fr')
            ->setPassword($this->userPasswordHasher->hashPassword($userWithoutFile, 'password'))
            ->setIsVerified(true)
            ->setCivility("Monsieur")
            ->setLogin("test-jb")
            ->setEnterprise("EntrepriseTest")
            ->setIsAdmin(false)
            ->setRoles(['ROLE_USER']);
        $manager->persist($userWithoutFile);

        $manager->flush();
    }
}
