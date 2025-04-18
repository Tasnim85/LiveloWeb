<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ForgotPasswordController extends AbstractController
{
    #[Route('/forgot/password', name: 'app_forgot_password')]
    public function index(): Response
    {
        return $this->render('forgot_password/forgotPassword.html.twig', [
            'controller_name' => 'ForgotPasswordController',
        ]);
    }
}
