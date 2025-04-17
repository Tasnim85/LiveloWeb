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
            // Vérifie le rôle de l'utilisateur pour rediriger vers la page appropriée
            if ($user->getRole() === 'client') {
                return $this->redirectToRoute('app_homeClient');  
            } else {
                return $this->redirectToRoute('app_homeAdmin');   
            }
        }
    
        $this->addFlash('error', 'Incorrect password.');
        return $this->redirectToRoute('app_login');
    }
    

    #[Route('/homeAdmin', name: 'app_homeAdmin')]
    public function homeAdminPage(): Response
    {
        return $this->render('home/homeAdmin.html.twig');
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

}