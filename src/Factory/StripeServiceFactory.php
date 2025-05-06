<?php
// src/Factory/StripeServiceFactory.php

use App\Service\StripeService; // Adjust the namespace as per your project structure

class StripeServiceFactory {
    public function __construct(private string $secretKey) {}
    
    public function create(): StripeService {
        return new StripeService($this->secretKey);
    }
}
?>