<?php

namespace App\Controller;

use App\Repository\FileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\BinaryFileResponse;


#[Route('/download')]
class DownloadController extends AbstractController
{
    #[Route('/{id}', name: 'app_admin_file_download', methods: ['GET'])]
    public function downloadFile(int $id, FileRepository $fileRepository): BinaryFileResponse
    {
        $path = str_ireplace("..", $this->getParameter('kernel.project_dir'), $fileRepository->findOneBy(['id' => $id])->getPath());
        return $this->file($path);

    }
}
