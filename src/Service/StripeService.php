<?php
namespace App\Service;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;

class StripeService
{
    private string $secretKey;

    public function __construct(string $secretKey)
    {
        $this->secretKey = $secretKey;
        Stripe::setApiVersion('2023-08-16');
        Stripe::setApiKey($secretKey);
    }

    public function createCheckoutSession(array $lineItems, string $successUrl, string $cancelUrl): Session
    {
        Stripe::setApiKey($this->secretKey);
        
        return Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $successUrl . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $cancelUrl,
            'automatic_tax' => ['enabled' => true],
        ]);
    }

    public function isPaymentCompleted(string $sessionId): bool
    {
        try {
            Stripe::setApiKey($this->secretKey);
            $session = Session::retrieve($sessionId);
            return $session->payment_status === 'paid';
        } catch (ApiErrorException $e) {
            return false;
        }
    }
}