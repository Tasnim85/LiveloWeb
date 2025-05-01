<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class LoginFormAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private JWTTokenManagerInterface $jwtManager,
        private UserProviderInterface $userProvider
    ) {}

    public function supports(Request $request): ?bool
    {
        return $request->isMethod('POST') && $request->attributes->get('_route') === 'app_login_check'
            || $request->cookies->has('BEARER');
    }

    public function authenticate(Request $request): Passport
    {
        if ($request->cookies->has('BEARER')) {
            $token = $request->cookies->get('BEARER');
            
            try {
                $payload = $this->jwtManager->parse($token);
                return new SelfValidatingPassport(
                    new UserBadge($payload['username'], 
                        fn($id) => $this->userProvider->loadUserByIdentifier($id))
                );
            } catch (\Exception $e) {
                throw new AuthenticationException('Invalid JWT token');
            }
        }

        return new Passport(
            new UserBadge($request->request->get('cin')),
            new PasswordCredentials($request->request->get('password'))
        );
    }

    public function onAuthenticationSuccess(
        Request $request, 
        TokenInterface $token, 
        string $firewallName
    ): ?Response {
        return null;
    }

    public function onAuthenticationFailure(
        Request $request, 
        AuthenticationException $exception
    ): ?Response {
        return new JsonResponse([
            'success' => false,
            'message' => $exception->getMessage()
        ], Response::HTTP_UNAUTHORIZED);
    }
}