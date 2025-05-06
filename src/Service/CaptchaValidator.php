<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CaptchaValidator
{
    private string $secret;
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client, string $recaptchaSecret)
    {
        $this->client = $client;
        $this->secret = $recaptchaSecret;
    }

    public function isValid(string $captchaResponse): bool
    {
        $response = $this->client->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
            'body' => [
                'secret' => $this->secret,
                'response' => $captchaResponse,
            ],
        ]);

        $data = $response->toArray(false);
        return $data['success'] ?? false;
    }
}
