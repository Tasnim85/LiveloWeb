<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class GeminiChatbotService
{
    private string $apiUrl;
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client, string $chatbotApiUrl)
    {
        $this->client = $client;
        $this->apiUrl = $chatbotApiUrl;
    }

    public function askGeminiWithValidation(string $userMessage): ?string
{
    // Liste des mots-clés associés à ton application écologique
    $keywords = ['écologique', 'livraison verte', 'vélo', 'trottinette', 'e-bike', 'durable' , 'mobilité douce', 'zéro émission', 'énergie propre', 'empreinte carbone',
    'logistique verte', 'transport durable', 'dernier kilomètre', 'eco-conception',
    'vélo cargo', 'mobilité urbaine', 'éco-responsable', 'livraison rapide verte',
    'ville intelligente', 'mobilité partagée', 'circulation douce',
    'transports alternatifs', 'mobilité électrique', 'logistique urbaine',
    'livraison propre', 'recharge électrique', 'écosystème vert',
    'solution durable', 'green delivery', 'livraison locale', 'mobilité verte' , 'catégorie', 'article', 'commande', 'trajet', 'utilisateur', 'partenaire',
    'client', 'livreur', 'livraison', 'panier', 'profil', 'statistiques',
    'dashboard', 'stock', 'quantité', 'prix', 'suivi', 'paiement',
    'notification', 'connexion', 'inscription', 'authentification',
    'chatbot', 'recherche', 'filtrage', 'tri', 'ajout', 'modification', 'suppression',
    'gains', 'revenus', 'produits', 'offre', 'disponibilité', 'adresse', 'horaire'];

    // Appel à l'API Gemini pour obtenir une réponse
    $response = $this->client->request('POST', $this->apiUrl, [
        'json' => [
            'contents' => [
                ['parts' => [['text' => $userMessage]]]
            ]
        ]
    ]);

    $data = $response->toArray(false);
    $geminiResponse = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

    if (!$geminiResponse) {
        return "Désolé, je n'ai pas compris votre question. Pouvez-vous reformuler ?";
    }

    // Vérification si la réponse contient des mots-clés pertinents
    $isValid = false;
    foreach ($keywords as $keyword) {
        if (stripos($geminiResponse, $keyword) !== false) {
            $isValid = true;
            break;
        }
    }

    // Si la réponse est valide (c'est-à-dire en rapport avec ton application écologique)
    if ($isValid) {
        return $geminiResponse;
    }

    // Sinon, on renvoie un message d'erreur
    return "Désolé, cette réponse ne semble pas être en rapport avec notre application écologique. Veuillez poser une question concernant nos services écologiques.";
}

}
