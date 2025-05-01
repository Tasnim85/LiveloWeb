<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Component\HttpFoundation\RedirectResponse; 

class GoogleAuthenticator extends OAuth2Authenticator
{
    public function __construct(
        private ClientRegistry               $clientRegistry,
        private EntityManagerInterface       $entityManager,
        private JWTTokenManagerInterface     $jwtManager,
        private SerializerInterface          $serializer,
        private UrlGeneratorInterface        $router,
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

    $jwt = $this->jwtManager->create($user);

    if ($user->getVerified()) {
        $routeName = match ($user->getRole()) {
            'client'          => 'app_homeClient',
            'admin'           => 'app_homeAdmin',
            'delivery_person' => 'app_homeLivreur',
            'partner'         => 'app_homePartner',
            default           => 'app_login',
        };
    } else {
        $routeName = match ($user->getRole()) {
            'delivery_person' => 'app_homeLivreurNotVerified',
            'admin'           => 'app_homeAdminNotVerified',
            'partner'         => 'app_homePartnerNotVerified',
            default           => 'app_login',
        };
    }

    $redirectUrl = $this->router->generate($routeName, [], UrlGeneratorInterface::ABSOLUTE_URL);

    $response = new RedirectResponse($redirectUrl);

    $response->headers->setCookie(new Cookie(
        'BEARER',
        $jwt,
        time() + 3600,
        '/',
        null,
        $request->isSecure(),
        true,   
        false, 
        $request->isSecure() ? 'None' : 'Lax'
    ));

    return $response;
}

    public function onAuthenticationFailure(Request $request, \Exception $exception): JsonResponse
    {
        return new JsonResponse([
            'success' => false,
            'message' => $exception->getMessage(),
        ], JsonResponse::HTTP_FORBIDDEN);
    }
}
