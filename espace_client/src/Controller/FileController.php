<?php

namespace App\Controller;

use App\Entity\File;
use App\Form\FileType;
use App\Repository\FileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class FileController extends AbstractController
{

    #[Route('/file/{id}/user_login={user_login}&category_label={category_label}', name: 'app_file_delete', methods: ['POST'])]
    public function delete(Request $request, File $file, FileRepository $fileRepository, string $user_login, string $category_label): Response
    {
        $path = $file->getPath();
        if ($this->isCsrfTokenValid('delete'.$file->getId(), $request->request->get('_token'))) {
            $fileRepository->remove($file, true);
        }
        if(file_exists($path)){
            unlink($path);
        }

        return $this->redirectToRoute('app_admin_file_index', ['user_login'=> $user_login,
            'category_label' => $category_label], Response::HTTP_SEE_OTHER);
    }
}
