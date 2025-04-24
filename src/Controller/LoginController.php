<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Psr\Log\LoggerInterface;

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
    public function loginCheck(
        Request $request,
        EntityManagerInterface $em,
        JWTTokenManagerInterface $jwtManager,
        UrlGeneratorInterface $urlGenerator,
        LoggerInterface $logger,
        SerializerInterface $serializer
    ): Response {
        $cin = $request->request->get('cin');
        $plainPassword = $request->request->get('password');

        $user = $em->getRepository(User::class)->findOneBy(['cin' => $cin]);

        if (!$user) {
            return $this->json([
                'success' => false,
                'message' => 'CIN not found.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (!password_verify($plainPassword, $user->getPassword())) {
            return $this->json([
                'success' => false,
                'message' => 'Incorrect password.'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $jwtManager->create($user);
        $userJson = $serializer->serialize($user, 'json', ['groups' => 'user:read']);
        $userData = json_decode($userJson, true);
        $logger->info('Generated JWT Token: ' . $token);
            
        dump($token);
        $redirectRoute = $user->getVerified() ? 
            match ($user->getRole()) {
                'client' => 'app_homeClient',
                'admin' => 'app_homeAdmin',
                'delivery_person' => 'app_homeLivreur',
                'partner' => 'app_homePartner',
                default => 'app_login',
            } : 
            match ($user->getRole()) {
                'delivery_person' => 'app_homeLivreurNotVerified',
                'admin' => 'app_homeAdminNotVerified',
                'partner' => 'app_homePartnerNotVerified',
                default => 'app_login',
            };
            $redirectUrl = $urlGenerator->generate($redirectRoute, [], UrlGeneratorInterface::ABSOLUTE_PATH);
        return $this->json([
            'success' => true,
            'token' => $token,
            'user' => $userData,
            'redirect' => $redirectUrl
        ]);
       
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