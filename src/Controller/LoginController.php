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
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final class LoginController extends AbstractController
{
    #[Route('/', name: 'app_login')]
    public function index(): Response
    {
        return $this->render('login/login.html.twig');
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
        try {
            $cin = $request->request->get('cin');
            $plainPassword = $request->request->get('password');

            $user = $em->getRepository(User::class)->findOneBy(['cin' => $cin]);
            if (!$user) {
                return $this->json(['success' => false, 'message' => 'CIN not found.'], Response::HTTP_UNAUTHORIZED);
            }
            if (!password_verify($plainPassword, $user->getPassword())) {
                return $this->json(['success' => false, 'message' => 'Incorrect password.'], Response::HTTP_UNAUTHORIZED);
            }

            $token = $jwtManager->create($user);
            $userData = json_decode($serializer->serialize($user, 'json', ['groups' => 'user:read']), true);
            $logger->info('Generated JWT Token: ' . $token);

            if ($user->getVerified()) {
                $codeVerificationRequired = !$user->isCodeUsed();
                $routeName = match ($user->getRole()) {
                    'client' => 'app_homeClient',
                    'admin' => 'app_homeAdmin',
                    'delivery_person' => 'app_homeLivreur',
                    'partner' => 'app_homePartner',
                    default => 'app_login',
                };
            } else {
                $codeVerificationRequired = false;
                $routeName = match ($user->getRole()) {
                    'delivery_person' => 'app_homeLivreurNotVerified',
                    'admin' => 'app_homeAdminNotVerified',
                    'partner' => 'app_homePartnerNotVerified',
                    default => 'app_login',
                };
            }
            $redirectUrl = $urlGenerator->generate($routeName, [], UrlGeneratorInterface::ABSOLUTE_PATH);

            $response = $this->json([
                'success'  => true,
                'token'    => $token,
                'user'     => $userData,
                'redirect' => $redirectUrl,
                'codeVerificationRequired' => $codeVerificationRequired,
            ]);
            $response->headers->setCookie(new Cookie(
                'BEARER', 
                $token,
                time() + 3600,
                '/',
                null,
                $request->isSecure(),
                true,
                false,
                $request->isSecure() ? 'None' : 'Lax'
            ));
            

            $secure = $request->isSecure();
            if ($request->request->has('remember-me')) {
                $rememberMeToken = bin2hex(random_bytes(32));
                $expiresAt = new \DateTimeImmutable('+30 days');

                $user->setRememberMeToken($rememberMeToken);
                $user->setRememberMeTokenExpiresAt($expiresAt);
                $em->flush();

                $cookie = new Cookie(
                    'REMEMBERME',
                    $rememberMeToken,
                    $expiresAt,
                    '/',
                    null,
                    $secure,
                    true,
                    false,
                    'lax'
                );
                $response->headers->setCookie($cookie);
            }

            return $response;

        } catch (\Throwable $e) {
            $logger->error('LoginCheck error: '.$e->getMessage(), ['exception' => $e]);
            return $this->json([
                'success' => false,
                'message' => 'Server error: '.$e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/remember_me', name: 'app_remember_me', methods: ['POST'])]
    public function rememberMe(
        Request $request,
        EntityManagerInterface $em,
        JWTTokenManagerInterface $jwtManager,
        SerializerInterface $serializer,
        UrlGeneratorInterface $urlGenerator
    ): Response {
        $cookieToken = $request->cookies->get('REMEMBERME');
        if (!$cookieToken) {
            return $this->json(['success' => false], Response::HTTP_UNAUTHORIZED);
        }

        $user = $em->getRepository(User::class)->findOneBy(['rememberMeToken' => $cookieToken]);
        if (!$user || $user->getRememberMeTokenExpiresAt() < new \DateTimeImmutable()) {
            $resp = $this->json(['success' => false], Response::HTTP_UNAUTHORIZED);
            $resp->headers->clearCookie('REMEMBERME');
            return $resp;
        }

        $newToken = $jwtManager->create($user);
        $newRememberMeToken = bin2hex(random_bytes(32));
        $expiresAt = new \DateTimeImmutable('+30 days');

        $user->setRememberMeToken($newRememberMeToken);
        $user->setRememberMeTokenExpiresAt($expiresAt);
        $em->flush();

        $cookie = new Cookie('REMEMBERME', $newRememberMeToken, $expiresAt, '/', null, true, true, false, 'lax');

        if ($user->getVerified()) {
            $codeVerificationRequired = !$user->isCodeUsed();
            $routeName = match ($user->getRole()) {
                'client' => 'app_homeClient',
                'admin' => 'app_homeAdmin',
                'delivery_person' => 'app_homeLivreur',
                'partner' => 'app_homePartner',
                default => 'app_login',
            };
        } else {
            $codeVerificationRequired = false;
            $routeName = match ($user->getRole()) {
                'delivery_person' => 'app_homeLivreurNotVerified',
                'admin' => 'app_homeAdminNotVerified',
                'partner' => 'app_homePartnerNotVerified',
                default => 'app_login',
            };
        }
        $redirectUrl = $urlGenerator->generate($routeName, [], UrlGeneratorInterface::ABSOLUTE_URL);

        $response = $this->json([
            'success'  => true,
            'token'    => $newToken,
            'user'     => json_decode($serializer->serialize($user, 'json', ['groups' => 'user:read']), true),
            'redirect' => $redirectUrl,
            'codeVerificationRequired' => $codeVerificationRequired,
        ]);
        $response->headers->setCookie($cookie);

        return $response;
    }

    #[Route('/verify-code', name: 'app_verify_code', methods: ['POST'])]
    public function verifyCode(Request $request, EntityManagerInterface $em): JsonResponse
    {
        try {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
            /** @var User $user */
            $user = $this->getUser();

            $data = json_decode($request->getContent(), true);
            $submittedCode = $data['code'] ?? '';

            if (!$user || !$user instanceof User) {
                throw new AccessDeniedException('User not authenticated');
            }

            if ($user->getVerificationCode() === $submittedCode && !$user->isCodeUsed()) {
                $user->setIsCodeUsed(true);
                $user->setVerificationCode(null); 
                $em->flush();
                return $this->json(['success' => true]);
            }

            return $this->json(['success' => false, 'message' => 'Invalid code'], Response::HTTP_BAD_REQUEST);

        } catch (\Exception $e) {
            return $this->json(['success' => false, 'message' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
        }
    }

    #[Route('/check-code-verification', name: 'app_check_code_verification', methods: ['GET'])]
    public function checkCodeVerification(): JsonResponse
    {
        try {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
            /** @var User $user */
            $user = $this->getUser();

            if (!$user instanceof User) {
                return $this->json(['codeVerificationRequired' => false]);
            }

            return $this->json([
                'codeVerificationRequired' => $user->getVerified() && !$user->isCodeUsed()
            ]);

        } catch (\Exception $e) {
            return $this->json(['codeVerificationRequired' => false]);
        }
    }

    #[Route('/logout', name: 'app_logout', methods: ['POST'])]
    public function logout(): void
    {
        throw new \LogicException('Logout is handled by Symfony.');
    }
    

    #[Route('/connect/google', name: 'app_connect_google')]
    public function connectGoogle(ClientRegistry $clientRegistry): RedirectResponse
    {
        return $clientRegistry->getClient('google')->redirect(['email', 'profile'], []);
    }

    #[Route('/connect/google/check', name: 'app_connect_google_check')]
    public function connectGoogleCheck(): Response
    {
        throw new \LogicException('This method should not be called directly.');
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