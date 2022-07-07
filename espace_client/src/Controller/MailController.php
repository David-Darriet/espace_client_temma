<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

#[Route('/mail')]
class MailController extends AbstractController
{
    #[Route('/{user_login}', name: 'app_admin_mail_register', methods: ['GET'])]
    public function sendMailLogin(MailerInterface              $mailer,
                                  ResetPasswordHelperInterface $resetPasswordHelper,
                                  string                       $user_login, UserRepository $userRepository)
    {
        try {
            $user = $userRepository->findOneByLogin($user_login);
            $resetToken = $resetPasswordHelper->generateResetToken($user);
            $email = (new TemplatedEmail())
                ->from(new Address(strval($_ENV["EMAIL"]), strval($_ENV["NAME_EMAIL"])))
                ->to($user->getEmail())
                ->subject('Identifiants personnels')
                ->htmlTemplate('mail/identifiants_premiere_connexion.html.twig')
                ->context([
                        'user' => $user,
                        'resetToken' => $resetToken,
                    ]

                );
            $mailer->send($email);
            $this->addFlash('success', 'Le mail d\'envoi des identifiants du client ' .
                $user->getEnterprise() . ' lui a bien été envoyé');
        }
        catch(\Exception $e){
            $this->addFlash('error', 'Impossible d\'envoyer le mail d\'envoi des identifiants du client ' .
                $user->getEnterprise() . '. Vérifiez auprès du client qu\'il ait bien réinitialisé son mot de passe avec le lien disponible dans le mail "Identifiants personnels".');
        }
        return $this->redirectToRoute('app_admin_index');
    }
}
