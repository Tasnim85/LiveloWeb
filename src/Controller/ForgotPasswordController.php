<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\SendGridMailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


final class ForgotPasswordController extends AbstractController
{
    #[Route('/forgot/password', name: 'app_forgot_password')]
    public function index(): Response
    {
        return $this->render('forgot_password/forgotPassword.html.twig');
    }

    #[Route('/forgot/password/send', name: 'app_forgot_password_send', methods: ['POST'])]
    public function sendResetCode(
        Request $request,
        EntityManagerInterface $em,
        SendGridMailer $mailer,
        SessionInterface $session
    ): JsonResponse {
        $email = $request->request->get('email');
        $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user) {
            return new JsonResponse(['success' => false, 'message' => 'Email not found.']);
        }

        $code = random_int(100000, 999999);
        $session->set('reset_code', $code);
        $session->set('reset_email', $email);

        $mailer->sendAdminNotification(
            $email,
            'Password Reset Code',
            "Your password reset code is: $code"
        );

        return new JsonResponse(['success' => true, 'message' => 'Code sent.']);
    }

    #[Route('/forgot/password/verify', name: 'app_forgot_password_verify', methods: ['POST'])]
    public function verifyCode(Request $request, SessionInterface $session): JsonResponse
    {
        $enteredCode = $request->request->get('code');
        $storedCode = $session->get('reset_code');

        if ((string)$enteredCode === (string)$storedCode) {
            return new JsonResponse(['success' => true]);
        }

        return new JsonResponse(['success' => false, 'message' => 'Invalid code.']);
    }

    #[Route('/forgot/password/reset', name: 'app_forgot_password_reset', methods: ['POST'])]
    public function resetPassword(
        Request $request,
        EntityManagerInterface $em,
        SessionInterface $session,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $email = $session->get('reset_email');
        $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user) {
            return new JsonResponse(['success' => false, 'message' => 'User not found.']);
        }

        $newPassword = $request->request->get('password');
        $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
        $user->setPassword($hashedPassword);
        $em->flush();

        $session->remove('reset_code');
        $session->remove('reset_email');

        return new JsonResponse(['success' => true, 'message' => 'Password updated.']);
    }
}
