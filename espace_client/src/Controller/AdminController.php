<?php

namespace App\Controller;

use App\Entity\File;
use App\Entity\User;
use App\Form\FileType;
use App\Form\ImportUserType;
use App\Form\UserType;
use App\Repository\CategoryRepository;
use App\Repository\FileRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

#[Route('/admin')]
class AdminController extends AbstractController
{

    #[Route('/{search}', name: 'app_admin_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, string $search = ""): Response
    {
        if ($search == "" && isset($_GET['search'])) {
            $search = $_GET['search'];
        }

        if ($search) {
            return $this->render('user/index.html.twig', [
                'users' => $userRepository->findByEnterprise($search),
            ]);
        } else {
            return $this->render('user/index.html.twig', [
                'users' => $userRepository->findBy(['isAdmin' => '0'], array('enterprise' => 'ASC')),
            ]);
        }

    }

    #[Route('/espace-client/{user_login}', name: 'app_admin_category_index', methods: ['GET'])]
    public function indexCategory(CategoryRepository $categoryRepository, UserRepository $userRepository, string $user_login): Response
    {
        return $this->render('category/index.html.twig', [
            'user' => $userRepository->findOneByLogin($user_login),
            'categories' => $categoryRepository->findBy(array(), array('label' => 'ASC')),
        ]);
    }

    #[Route('/user/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request                      $request, UserRepository $userRepository,
                        UserPasswordHasherInterface  $userPasswordHasher,
                        ResetPasswordHelperInterface $resetPasswordHelper,
                        MailerInterface              $mailer,
                        MailController               $mailController): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $mdpRandom = User::randomPassword();
        $user->setPassword($userPasswordHasher->hashPassword($user, $mdpRandom));

        if ($form->isSubmitted() && $form->isValid()) {
            $roles = [];
            if (isset($_POST['user']['isAdmin'])) {
                $roles[] = 'ROLE_ADMIN';
            } else {
                $roles[] = 'ROLE_USER';
            }
            $entreprise = $_POST['user']['enterprise'];
            $user->setLogin($entreprise);
            $user->setRoles($roles);

            try {
                $userRepository->add($user, true);
                $this->addFlash('success', 'Utilisateur créé et l\'email contenant les identifiants de 
                première connexion du nouvel utilisateur vient d\'être envoyé à l\'adresse mail renseignée.');
            } catch (ORMException $ORMException) {
                $this->addFlash('error', 'Utilisateur non créé.');
            } catch (\Exception $e) {
                $this->addFlash('error', 'L\'email contenant les identifiants de 
                première connexion du nouvel utilisateur n\'a pas pu être envoyé à l\'adresse mail renseignée');
            }

            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }


    #[Route('/espace-client/{user_login}/{category_label}', name: 'app_admin_file_index', methods: ['GET'])]
    public function indexFileUserCategory(FileRepository $fileRepository, string $user_login, string $category_label, UserRepository $userRepository, CategoryRepository $categoryRepository): Response
    {
        return $this->render('file/index.html.twig', [
            'user' => $userRepository->findOneByLogin($user_login),
            'category' => $categoryRepository->findOneByLabel($category_label),
            'files' => $fileRepository->findByUserAndCategory($user_login, $category_label),
        ]);
    }

    #[Route('/espace-client/{user_login}/file/new', name: 'app_admin_file_new', methods: ['GET', 'POST'])]
    public function addFileUserCategory(Request            $request,
                                        FileRepository     $fileRepository,
                                        string             $user_login,
                                        UserRepository     $userRepository,
                                        CategoryRepository $categoryRepository,
                                        MailerInterface    $mailer): Response
    {
        $file = new File();
        $categories = $categoryRepository->findAll();
        $form = $this->createForm(FileType::class, $file, array(
            'categories' => $categories
        ));
        $user = $userRepository->findOneByLogin($user_login);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $documentFile = $form['document']->getData();
                if (pathinfo($documentFile->getClientOriginalName(), PATHINFO_EXTENSION) === 'pdf'
                    ||
                    pathinfo($documentFile->getClientOriginalName(), PATHINFO_EXTENSION) === 'xlsx'
                    ||
                    pathinfo($documentFile->getClientOriginalName(), PATHINFO_EXTENSION) === 'xls') {
                    $category_name = $_POST['file']['category'];
                    $uploadDirectory = '../src/Document/' . $user_login . "/" . $category_name;
                    if (file_exists($uploadDirectory . "/" . $documentFile->getClientOriginalName())) {
                        $compteur = 1;
                        $passage = false;
                        while ($passage == false) {
                            if (!file_exists($uploadDirectory . "/" . pathinfo($documentFile->getClientOriginalName(), PATHINFO_FILENAME) . "(" . $compteur . ")." . pathinfo($documentFile->getClientOriginalName(), PATHINFO_EXTENSION))) {
                                $passage = true;
                                $file->setName(pathinfo($documentFile->getClientOriginalName(), PATHINFO_FILENAME) . "(" . $compteur . ")." . pathinfo($documentFile->getClientOriginalName(), PATHINFO_EXTENSION));
                                $file->setPath($uploadDirectory . "/" . pathinfo($documentFile->getClientOriginalName(), PATHINFO_FILENAME) . "(" . $compteur . ")." . pathinfo($documentFile->getClientOriginalName(), PATHINFO_EXTENSION));
                                $documentFile->move($uploadDirectory, pathinfo($documentFile->getClientOriginalName(), PATHINFO_FILENAME) . "(" . $compteur . ")." . pathinfo($documentFile->getClientOriginalName(), PATHINFO_EXTENSION));
                            }
                            $compteur++;
                        }
                    } else {
                        $file->setName($documentFile->getClientOriginalName());
                        $file->setPath($uploadDirectory . "/" . $documentFile->getClientOriginalName());
                        $documentFile->move($uploadDirectory, $documentFile->getClientOriginalName());

                    }
                    $file->setUser($userRepository->findOneByLogin($user_login));
                    $file->setCreatedAt(new \DateTimeImmutable());
                    $file->setFormat(pathinfo($documentFile->getClientOriginalName(), PATHINFO_EXTENSION));;
                    $category = $categoryRepository->findOneByLabel($category_name);
                    $file->setCategory($category);

                    $this->addFile($fileRepository, $file, $user, $mailer);
                    return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
                } else {
                    $this->addFlash('error', 'Le fichier que vous venez de tenter d\'ajouter n\'est pas conforme. Les fichiers ne possédant pas de format ne sont pas acceptés. Les formats acceptés sont les formats pdf, xlsx et xls.');
                }
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de l\'import. Le fichier que vous venez de tenter d\'ajouter n\'est pas conforme.');
            }
        }

        return $this->renderForm('file/new.html.twig', [
            'user' => $userRepository->findOneByLogin($user_login),
            'file' => $file,
            'form' => $form,
        ]);
    }

    function addFile($fileRepository, $file, $user, $mailer)
    {
        try {
            $fileRepository->add($file, true);
            $this->addFlash('success', 'Le fichier ' . $file->getName() . ' a bien été ajouté dans la catégorie '
                . $file->getCategory()->getLabel() . ', pour le client ' . $user->getEnterprise() . '.');
            $this->sendMailAlertNewFile($user, $file, $mailer);
        } catch (\Exception $e) {
            $this->addFlash('error', 'Le fichier .' . $file->getName() . ' n\'a pas été ajouté pour le client ' . $user->getEnterprise() . '.');
        }
    }

    function sendMailAlertNewFile($user, $file, $mailer)
    {
        try {
            $email = (new TemplatedEmail())
                ->from(new Address(strval($_ENV["EMAIL"]), strval($_ENV["NAME_EMAIL"])))
                ->to($user->getEmail())
                ->subject('Nouveau fichier disponible')
                ->htmlTemplate('mail/alerte_nouveau_fichier_dispo.html.twig')
                ->context([
                        'user' => $user,
                        'category_name' => $file->getCategory()->getLabel(),
                        'file_name' => $file->getName(),
                        'file_format' => $file->getFormat(),
                    ]
                );
            $mailer->send($email);
            $this->addFlash('success', 'Le mail notifiant le client qu\'un nouveau fichier 
            est disponible dans son espace client vient d\'être envoyé.');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Le mail notifiant le client qu\'un nouveau fichier 
            est disponible dans son espace client n\'a pas pu être envoyé.');
        }
    }

    #[Route('/import/user/add', name: 'app_admin_import_user', methods: ['GET', 'POST'])]
    public function importUserByCSV(Request                      $request, UserRepository $userRepository,
                                    UserPasswordHasherInterface  $userPasswordHasher,
                                    ResetPasswordHelperInterface $resetPasswordHelper,
                                    MailerInterface              $mailer): Response
    {
        $form = $this->createForm(ImportUserType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $documentFile = $form['document']->getData();
            if (pathinfo($documentFile->getClientOriginalName(), PATHINFO_EXTENSION) === 'csv') {
                $uploadDirectory = '../src/Document/ImportUser/';
                $name = pathinfo($documentFile->getClientOriginalName(), PATHINFO_FILENAME);
                $path = $name . "." . pathinfo($documentFile->getClientOriginalName(), PATHINFO_EXTENSION);
                $documentFile->move($uploadDirectory, $path);
                $new_path = $uploadDirectory . $path;

                if (file_exists($new_path)) {
                    $array = [];
                    $file_csv = fopen($new_path, "r");
                    $data = fgetcsv($file_csv, 1000, ",");
                    while (($data = fgetcsv($file_csv, 1000, ",")) !== FALSE) {
                        // Read the data
                        $array[] = $data;
                    }
                    fclose($file_csv);
                    try {
                        for ($i = 0; $i < count($array); $i++) {
                            $user = new User();
                            $user->setRoles(["ROLE_USER"])
                                ->setIsAdmin(0)
                                ->setEnterprise(str_ireplace("\x92", "'", strval($array[$i][0])))
                                ->setCivility(strval($array[$i][1]))
                                ->setLastname(str_ireplace("\x92", "'", strval($array[$i][2])))
                                ->setFirstname(str_ireplace("\x92", "'", strval($array[$i][3])))
                                ->setEmail(strval($array[$i][4]))
                                ->setLogin($array[$i][0]);
                            $mdpRandom = User::randomPassword();
                            $user->setPassword($userPasswordHasher->hashPassword($user, $mdpRandom));
                            if ($userRepository->findOneBy(['email' => strval($array[$i][4])]) == null) {
                                $userRepository->add($user, true);
                            }
                        }
                        $this->addFlash('success', 'Import réalisé avec succès.');

                        if (file_exists($new_path)) {
                            unlink($new_path);
                        }
                    } catch (\Exception $e) {
                        $this->addFlash('error', 'L\'email contenant les identifiants de 
                première connexion du nouvel utilisateur n\'a pas pu être envoyé à l\'adresse mail renseignée');
                    } catch (ORMException $ORMException) {
                        $this->addFlash('error', 'Utilisateur non créé.');
                    }
                }
            } else {
                $this->addFlash('error', 'Le fichier que vous venez de tenter d\'ajouter n\'est pas conforme. Les fichiers ne possédant pas de format ne sont pas acceptés. Seul le format csv est accepté.');
            }

            return $this->redirectToRoute('app_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/import_user.html.twig', [
            'form' => $form,
        ]);
    }
}
