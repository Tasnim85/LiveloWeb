<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class QRCodeController extends AbstractController
{
    public function __construct(
        private JWTTokenManagerInterface $jwtManager,
        private SerializerInterface $serializer,
    ) {}

    #[Route('/q/r/code', name: 'app_qr_code')]
    public function index(): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('User not authenticated.');
        }

        $userData = json_decode(
            $this->serializer->serialize($user, 'json', ['groups' => 'user:read']),
            true
        );

        $payload = json_encode([
            'user' => $userData,
        ]);

        return $this->render('qr_code/index.html.twig', [
            'payload' => $payload, 
        ]);
    }
}
