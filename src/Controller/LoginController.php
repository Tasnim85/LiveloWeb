<?php

namespace App\Controller;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LoginController extends AbstractController
{
    #[Route('/', name: 'app_login')]
    public function index(): Response
    {
        return $this->render('login/login.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }
    #[Route('/login_check', name: 'app_login_check', methods: ['POST'])]
    public function loginCheck(Request $request, EntityManagerInterface $em): Response
    {
        $cin = $request->request->get('cin');
        $plainPassword = $request->request->get('password');

        $user = $em->getRepository(User::class)->findOneBy(['cin' => $cin]);

        if (!$user) {
            $this->addFlash('error', 'CIN not found.');
            return $this->redirectToRoute('app_login');
        }

        if (password_verify($plainPassword, $user->getPassword())) {
            if (!$user->getVerified()) {
                return match ($user->getRole()) {
                    'delivery_person' => $this->redirectToRoute('app_homeLivreurNotVerified'),
                    'admin' => $this->redirectToRoute('app_homeAdminNotVerified'),
                    'delivery_person' => $this->redirectToRoute('app_homeLivreurNotVerified'),
                    'partner' => $this->redirectToRoute('app_homePartnerNotVerified'),
                    default => $this->redirectToRoute('app_login'),
                };
            }

            return match ($user->getRole()) {
                'client' => $this->redirectToRoute('app_homeClient'),
                'admin' => $this->redirectToRoute('app_homeAdmin'),
                'delivery_person' => $this->redirectToRoute('app_homeLivreur'),
                'partner' => $this->redirectToRoute('app_homePartner'),
                default => $this->redirectToRoute('app_login'),
            };
        }

        $this->addFlash('error', 'Incorrect password.');
        return $this->redirectToRoute('app_login');
    }


    
    #[Route('/homeAdminNotVerified', name: 'app_homeAdminNotVerified')]
    public function homeAdminNotVerified(): Response
    {
        return $this->render('home/homeAdminNotVerified.html.twig');
    }
    #[Route('/homeLivreurNotVerified', name: 'app_homeLivreurNotVerified')]
    public function homeLivreurNotVerified(): Response
    {
        return $this->render('home/homeLivreurNotVerified.html.twig');
    }
    #[Route('/homePartnerNotVerified', name: 'app_homePartnerNotVerified')]
    public function homePartnerNotVerified(): Response
    {
        return $this->render('home/homePartnerNotVerified.html.twig');
    }
    #[Route('/homeAdmin', name: 'app_homeAdmin')]
    public function homeAdminPage(): Response
    {
        return $this->render('home/homeAdmin.html.twig');
    }
    #[Route('/homeLivreur', name: 'app_homeLivreur')]
    public function homeLivreurPage(): Response
    {
        return $this->render('home/home_livreur.html.twig');
    }
    #[Route('/homePartner', name: 'app_homePartner')]
    public function homePartner(): Response
    {
        return $this->render('home/homePartner.html.twig');
    }
    #[Route('/homeClient', name: 'app_homeClient')]
    public function homeClientPage(): Response
    {
        return $this->render('home/homeClient.html.twig');
    }
    #[Route('/signup', name: 'sigup')]
    public function NavigateToSignUp(): Response
    {
        return $this->render('sign_up/signup.html.twig');
    }
    #[Route('/forgotPassword', name: 'forgotPassword')]
    public function ForgotPassword(): Response
    {
        return $this->render('forgot_password/forgotPassword.html.twig');
    }

}