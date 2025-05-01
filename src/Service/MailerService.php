<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\DataPart;

class MailerService
{
    public function __construct(private MailerInterface $mailer) {}

    // public function sendEmail(
    //     $to="",
    //     $subject="",
    //     $content=""
    // ): void
    // {
    //     $email = (new Email())
    //         ->from(new Address(address: 'Livelo.support@gmail.com', 'Livelo Support'))
    //         ->to($to)
    //         ->subject($subject)
    //         ->text('Sending emails is fun again!')
    //         ->html($content);

    //         $this->mailer->send($email);
    // }

    public function sendEmailWithAttachment(
        $to,
        $subject,
        $content,
        $attachment,
        $filename
    ): void
    {
        $email = (new Email())
            ->from(new Address('Mediplus.support@gmail.com', 'Mediplus Support'))
            ->to($to)
            ->subject($subject)
            ->html($content)
            ->addPart(new DataPart($attachment, $filename, 'Presecription/pdf'));

        $this->mailer->send($email);
    }

    public function sendQrEmail(string $to, string $subject, string $content, string $qrImagePath): void
    {
        $email = (new Email())
        ->from(new Address('Livelo.support@gmail.com', 'Livelo Support'))
        ->to($to)
            ->subject($subject)
            ->html("
                <p>{$content}</p>
                <p>Voici votre QR Code :</p>
                <img src=\"cid:qrcode\">
            ")
            ->embedFromPath($qrImagePath, 'qrcode');

        $this->mailer->send($email);
    }

}