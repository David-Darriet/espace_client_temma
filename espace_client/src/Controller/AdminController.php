<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\CategoryRepository;
use App\Repository\FileRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{

    #[Route('/', name: 'app_admin_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/{user_login}', name: 'app_admin_category_index', methods: ['GET'])]
    public function indexCategory(CategoryRepository $categoryRepository, UserRepository $userRepository, string $user_login): Response
    {
        return $this->render('category/index.html.twig', [
            'user'=> $userRepository->findOneByLogin($user_login),
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/user/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $mdpRandom = User::randomPassword();
        $user->setPassword($userPasswordHasher->hashPassword($user,$mdpRandom));

        if ($form->isSubmitted() && $form->isValid()) {
            $roles = [];
            if(isset($_POST['user']['isAdmin'])){
                $roles[] = 'ROLE_ADMIN';
            }else{
                $roles[] = 'ROLE_USER';
            }
            $entreprise = $_POST['user']['enterprise'];
            $user->setLogin($entreprise);
            $user->setRoles($roles);
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{user_login}/{category_label}', name: 'app_admin_file_index', methods: ['GET'])]
    public function indexFileUserCategory(FileRepository $fileRepository, string $user_login, string $category_label, UserRepository $userRepository, CategoryRepository $categoryRepository): Response
    {
        return $this->render('file/index.html.twig', [
            'user' => $userRepository->findOneByLogin($user_login),
            'category' => $categoryRepository->findOneByLabel($category_label),
            'files' => $fileRepository->findByUserAndCategory($user_login, $category_label),
        ]);
    }
}
