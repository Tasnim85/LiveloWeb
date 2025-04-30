<?php

namespace App\Controller;

use App\Service\TwilioService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SmsTestController extends AbstractController
{
    #[Route('/test/sms', name: 'app_test_sms')]
    public function testSms(TwilioService $twilioService): Response
    {
        $twilioService->sendSms('+21652887287', '+15154171765', 'Un nouvel article a été ajouté');

        return new Response('SMS envoyé avec succès');
    }
}
