<?php

namespace App\Service;

use SendGrid;
use SendGrid\Mail\Mail;

class SendGridMailer
{
    private $sendGrid;

    public function __construct(string $sendGridApiKey)
    {
        $this->sendGrid = new SendGrid($sendGridApiKey);
    }

    public function sendAdminNotification(string $toEmail, string $subject, string $content): void
    {
        $email = new Mail();
        $email->setFrom("tasnimbenhassine1@gmail.com", "Livelo");
        $email->setSubject($subject);
        $email->addTo($toEmail);
        $email->addContent("text/plain", $content);

        try {
            $this->sendGrid->send($email);
        } catch (\Exception $e) {
        }
    }
}
