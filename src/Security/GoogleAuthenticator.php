<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
class GoogleAuthenticator extends OAuth2Authenticator
{
    public function __construct(
        private ClientRegistry $clientRegistry,
        private EntityManagerInterface $entityManager,
        private RouterInterface $router,
    ) {}

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'app_connect_google_check';
    }

    public function authenticate(Request $request): Passport
    {
        $client = $this->clientRegistry->getClient('google');
        $accessToken = $this->fetchAccessToken($client);

        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function() use ($accessToken, $client) {
                /** @var GoogleUser $googleUser */
                $googleUser = $client->fetchUserFromToken($accessToken);
                
                $email = $googleUser->getEmail();
                
                $existingUser = $this->entityManager->getRepository(User::class)
                    ->findOneBy(['email' => $email]);

                if ($existingUser) {
                    return $existingUser;
                }

                $user = new User();
                $user->setEmail($email);
                $user->setNom($googleUser->getFirstName());
                $user->setPrenom($googleUser->getLastName());
                $user->setRole('admin'); 
                $user->setVerified(true);
                $user->setCin(uniqid()); 
                $user->setPassword(''); 

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                return $user;
            })
        );
    }
    

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        /** @var User $user */
        $user = $token->getUser();
        
        // Use the same redirection logic as your regular login
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

        $redirectUrl = $this->router->generate($redirectRoute, [], UrlGeneratorInterface::ABSOLUTE_PATH);

        return new RedirectResponse($redirectUrl);
    }
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());
        return new Response($message, Response::HTTP_FORBIDDEN);
    }
}