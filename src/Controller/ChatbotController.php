<?php

namespace App\Controller;

use App\Service\GeminiChatbotService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ChatbotController extends AbstractController
{
    #[Route('/chatbot/ask', name: 'chatbot_ask', methods: ['POST'])]
    public function ask(Request $request, GeminiChatbotService $chatbot): JsonResponse
    {
        $userMessage = $request->request->get('message');

        $response = $chatbot->askGeminiWithValidation($userMessage);

        return $this->json([
            'response' => $response,
        ]);
    }
    #[Route('/chatbot', name: 'chatbot_page', methods: ['GET'])]
public function chatbotPage(): Response
{
    return $this->render('chatbot/chatbot.html.twig');
}

}
